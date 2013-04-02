<?php
class PostgresDatabase extends Database {

    protected $comparison = array("=", "<>", "!=", "<=", "<", ">=", ">", "<=>", "LIKE", "REGEXP");
    protected $logic = array("or", "or not", "||", "xor", "and", "and not", "&&", "not");
    protected $cachedSchemas = array();

    private function columnValue($value, $column = null) {
        switch($column) {
            case "boolean":
                if($value === true) {
                    return "1";
                } elseif($value === false) {
                    return "0";
                } else {
                    return !empty($value) ? "1" : "0";
                }
            case "integer":
            case "float":
                if($value === "" or is_null($value)) {
                    return "NULL";
                } elseif(is_numeric($value)) {
                    return $value;
                }
            default:
                if(is_null($value)) {
                    return "NULL";
                }
                
                return "'" . pg_escape_string($this->connection, $value) . "'";
        }
    }

    private function columnType($column) {
        preg_match("/([a-z]*)\(?([^\)]*)?\)?/", $column, $type);
        list($column, $type, $limit) = $type;
        
        if(in_array($type, array("date", "time", "datetime", "timestamp"))) {
            return $type;
        } elseif(($type == "tinyint" && $limit == 1) || $type == "boolean") {
            return "boolean";
        } elseif(strstr($type, "int")) {
            return "integer";
        } elseif(strstr($type, "char") || $type == "tinytext") {
            return "string";
        } elseif(strstr($type, "text")) {
            return "text";
        } elseif(strstr($type, "blob") || $type == "binary") {
            return "binary";
        } elseif(in_array($type, array("float", "double", "real", "decimal"))) {
            return "float";
        } elseif($type == "enum" || $type = "set") {
            return "{$type}($limit)";
        }
    }

    private function sqlConditions($table, $conditions, $logical = "AND") {
        if(is_array($conditions)) {
            $sql = array();
            
            foreach($conditions as $key => $value) {
                if(is_numeric($key)) {
                    if(is_string($value)) {
                        $sql[]= $value;
                    } else {
                        $sql[]= "(" . $this->sqlConditions($table, $value) . ")";
                    }
                } else {
                    if(in_array($key, $this->logic)) {
                        $sql []= "(" . $this->sqlConditions($table, $value, strtoupper($key)) . ")";
                    } elseif(is_array($value)) {
                        foreach($value as $k => $v) {
                            $value[$k] = $this->columnValue($v, null);
                        }
                        
                        if(preg_match("/([\w_]+) (BETWEEN)/", $key, $regex)) {
                            $condition = $regex[1] . " BETWEEN " . join(" AND ", $value);
                        } else {
                            $condition = $key . " IN (" . join(",", $value) . ")";
                        }
                        
                        $sql[]= $condition;
                    } else {
                        $comparison = "=";
                    
                        if(preg_match("/([\w_]+) (" . join("|", $this->comparison) . ")/", $key, $regex)) {
                            list($regex, $key, $comparison) = $regex;
                        }
                        
                        $schema = $this->schema($table);
                        $column = $schema[$key]["type"];
                        $columnType = $this->columnType($column);
                        $value = $this->columnValue($value, $columnType);
                        $sql[]= "{$key} {$comparison} {$value}";
                    }
                }
            }
            
            $sql = join(" {$logical} ", $sql);
        } else {
            $sql = $conditions;
        }
        
        return $sql;
    }

	protected function hasResult() {
		return is_resource($this->results);	
	}

    protected function fetchRow($results = null) {
    	$results = is_null($results) ? $this->results : $results;
        return pg_fetch_assoc($results);
    }
	
    public function connect() {
    	if (!$this->connected) {
        	$host = ArrayHelper::getValue("host", $this->config);
            $user = ArrayHelper::getValue("user", $this->config);
            $password = ArrayHelper::getValue("password", $this->config);
            $database = ArrayHelper::getValue("database", $this->config);

            $this->connection = @pg_connect("host={$host} port=5432 dbname={$database} user={$user} password={$password}");

            if (!$this->connection) {
                $this->error($this->errorTitle, "Can`t connect to database server.");
            }

            $this->connected = true;
        }
    }

    public function close() {
    	if (isset($this->connection) && $this->connected) {
            @pg_close($this->connection);
        }
    }
    
    public function query($sql) {
    	$this->log($sql);
        $this->connect();
        $this->results = pg_query($this->connection, $sql);

        if (!$this->results) {
            $this->error($this->errorTitle, "Error to execute a query in database.");
        }
        
        return $this->results;
    }

    public function fetch($sql = null) {
    	if(!is_null($sql) && !$this->query($sql)) {
            return false;
        } else if($this->hasResult()) {
			return $this->fetchRow();	
        } else {
            return null;
        }
    }

    public function fetchAll($sql = null) {
    	if(!is_null($sql) && !$this->query($sql)) {
            return false;
        } else if($this->hasResult()) {
            $results = array();

            while($result = $this->fetch()) {
                $results []= $result;
            }

            return $results;
        } else {
            return null;
        }
    }

    public function schema($table) {
        $cachedSchema = ArrayHelper::getValue($table, $this->cachedSchemas);

        if (!is_null($cachedSchema)) {
            return $cachedSchema;
        }

        $sql = "SELECT column_name, column_default, is_nullable, is_identity, data_type,";
        $sql .= " (";
        $sql .= " SELECT 'YES' FROM information_schema.key_column_usage a";
        $sql .= " INNER JOIN information_schema.table_constraints b";
        $sql .= " ON a.constraint_name = b.constraint_name AND c.column_name = a.column_name";
        $sql .= " WHERE a.table_name = 'cliente' AND b.constraint_type = 'PRIMARY KEY'";
        $sql .= " ) AS is_primarykey";
        $sql .= " FROM information_schema.columns AS c";
        $sql .= " WHERE c.table_name = '{$table}'";
        $sql .= " ORDER BY c.ordinal_position";

        if(!$this->query($sql)) {
            return false;
        }

        $columns = $this->fetchAll();
        $schema = array();

        foreach($columns as $column) {
            $schema[$column["column_name"]] = array(
                "type" => $this->columnType($column["data_type"]),
                "null" => $column["is_nullable"] == "YES" ? true : false,
                "default" => $column["column_default"],
                "key" => false,
                "primaryKey" => $column["is_primarykey"] == "YES" ? true : false,
                "autoIncrement" => $column["is_identity"] == "YES" ? true : false
            );
        }
        
        $this->cachedSchemas[$table] = $schema;
        return $schema;
    }
    
    public function read($table = null, $params = array()) {
        $data = array(
            "table" => $table,
            "fields" => is_array($f = ArrayHelper::getValue("fields", $params)) ? join(",", $f) : $f,
            "conditions" => ($c = $this->sqlConditions($table, ArrayHelper::getValue("conditions", $params))) ? "WHERE {$c}" : "",
            "order" => is_null(ArrayHelper::getValue("order", $params)) ? "" : "ORDER BY {$params['order']}",
            "groupBy" => is_null(ArrayHelper::getValue("groupBy", $params)) ? "" : "GROUP BY {$params['groupBy']}",
            "limit" => is_null(ArrayHelper::getValue("limit", $params)) ? "" : "LIMIT {$params['limit']}"
        );
            
        $query = "SELECT {$data['fields']} FROM {$data['table']} {$data['conditions']} {$data['groupBy']} {$data['order']} {$data['limit']}";
        $result = $this->fetchAll($query);
        return $result;
    }

    public function count($table, $field = "*", $params = array()) {
        $data = array(
            "table" => $table,
            "fields" => $field,
            "conditions" => ($c = $this->sqlConditions($table, ArrayHelper::getValue("conditions", $params))) ? "WHERE {$c}" : "",
            "groupBy" => is_null(ArrayHelper::getValue("groupBy", $params)) ? "" : "GROUP BY {$params['groupBy']}",
        );

        $query = "SELECT COUNT({$data['fields']}) AS count FROM {$data['table']} {$data['conditions']} {$data['groupBy']}";
        $result = $this->fetch($query);
        return $result["count"];
    }
	
    public function insert($table, $data = array()) {
        $fields = "";
        $values = "";
        $x = 0;
        $schema = $this->schema($table);
        $primaryKeyColumn = null;

        foreach ($schema as $key => $value) {
            if (ArrayHelper::getValue("primaryKey", $value)) {
                $primaryKeyColumn = $key;
            }
        }
        
        foreach ($data as $key => $value) {
            $value = $this->columnValue($value, $schema[$key]["type"]);
            $fields .= $x == 0 ? "$key" : ", $key";
            $values .=  $x == 0 ? $value : ", $value";
            $x++;
        }

        if (!is_null($primaryKeyColumn)) {
            $query = "INSERT INTO $table ($fields) VALUES ($values) RETURNING {$primaryKeyColumn}";            
            $result = $this->fetch($query);
            return $result[$primaryKeyColumn];
        } else {
            $query = "INSERT INTO $table ($fields) VALUES ($values)";
            $result = $this->query($query);
            return $result;
        }
    }

    public function update($table, $data = array(), $conditions = array()) {
        $values = "";
        $x = 0;
        $schema = $this->schema($table);

        foreach ($data as $key => $value) {
            $column = $schema[$key];
            $value = $this->columnValue($value, $schema[$key]["type"]);
            $values .= $x == 0 ? "$key = $value" : ", $key = $value";
            $x++;
        }
        
        $conditions = $this->sqlConditions($table, $conditions);
        $query = "UPDATE $table SET $values WHERE $conditions";
        $result = $this->query($query);
        return $result;
    }

    public function delete($table, $conditions = array()) {
        $conditions = $this->sqlConditions($table, $conditions);
        $query = "DELETE FROM $table WHERE $conditions";
        $result = $this->query($query);
        return $result;
    }

    public function insertedId() {
        throw new Exception("This method is not supported for PostgresDatabase.", 1);
    }

    public function beginTransaction() {
        return $this->transactionStarted = $this->query("START TRANSACTION");
    }
    
    public function commitTransaction() {
        $this->transactionStarted = !$this->query("COMMIT");
        return !$this->transactionStarted;
    }
    
    public function rollbackTransaction() {
        $this->transactionStarted = !$this->query("ROLLBACK");
        return !$this->transactionStarted;
    }
}
?>