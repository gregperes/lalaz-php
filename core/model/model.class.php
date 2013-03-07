<?php
/**
 * Represents the Model of the application.
 * The Model is the base class to access databases.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class Model extends Object {

    private $environment;
    private $conn;
    private $table;
    private $schema = array();
    private $primaryKey;
    private $id;
    
    private function getFieldsWithValues($data) {
        $result = array();
        
        foreach ($this->schema as $field => $describe) {
            if (!$describe["autoIncrement"]) {
                foreach ($data as $key => $value) {
                    if ($key == $field) {
                        $result[$field] = $value;
                        break;
                    }
                }
            }
        }
        
        return $result;
    }
    
    protected function getConn() {
        if (is_null($this->conn)) {
            $this->conn = DatabaseFactory::createDatabase();
        }
        
        return $this->conn;
    }
    
    protected function getTable() {
        return $this->table;
    }
    
    protected function setTable($value) {
        $this->table = $value;
        $this->schema = $this->conn->schema($value);
        
        foreach($this->schema as $field => $describe) {
            if($describe["primaryKey"]) {
                $this->primaryKey = $field;
                break;
            }
        }
    }
    
    public function __construct() {
        $this->errorTitle = "Model Error";
        $this->environment = Config::read("environment");
        $this->conn = DatabaseFactory::createDatabase();     

        if (empty($this->table)) {
            $databaseConfig = Config::read("database");
            $tablePrefix = $databaseConfig[$this->environment]["prefix"];
            
            $this->table =  StringHelper::underscore($tablePrefix . get_class($this));
            $this->setTable($this->table);
        }
    }
    
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    public function fetch($sql) {
        return $this->conn->fetch($sql);
    }
    
    public function fetchAll($sql) {
        return $this->conn->fetchAll($sql);
    }
    
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    public function commitTransaction() {
        return $this->conn->commitTransaction();
    }
    
    public function rollbackTransaction() {
        return $this->conn->rollbackTransaction();
    }
    
    public function all($params = array()) {
        $data = array(
            "fields" => array_keys($this->schema),
            "conditions" => ArrayHelper::getValue("conditions", $params),
            "order" => ArrayHelper::getValue("order", $params),
            "limit" => ArrayHelper::getValue("limit", $params)
        );
        
        $results = $this->conn->read($this->table, $data);
        return $results;
    }
    
    public function first($params = array()) {
        $params = array_merge(
            array("conditions" => $params),
            array("limit" => 1)
        );
        
        $results = $this->all($params);
        return empty($results) ? null : $results[0];
    }
    
    public function count($conditions = array()) {
        $params = array_merge(
            array("fields" => "*", "conditions" => $conditions)
        );
        
        return $db->count($this->table, $params);
    }
    
    public function exists($id) {
        $conditions = array("$this->primaryKey" => $id);
        $count = $this->count($this->table, $this->primaryKey, $conditions);
        return $count > 0;
    }
    
    public function insert($data = array()) {
        $data = $this->getFieldsWithValues($data);
        $result = $this->conn->insert($this->table, $data);
        $this->id = $this->conn->insertedId();
        return $result;
    }
    
    public function update($id, $data = array()) {
        $conditions = array($this->primaryKey => $id);
        $result = $this->conn->update($this->table, $this->getFieldsWithValues($data), $conditions);
        return $result;
    }
    
    public function save($data = array()) {
        if (!array_key_exists($this->primaryKey, $data)) {
            $this->insert($data);
        } else {
            $id = $data[$this->primaryKey];
            unset($data[$this->primaryKey]);
            $this->update($id, $data);
        }
    }
    
    public function delete($id) {
        $conditions = array($this->primaryKey => $id);
        $result = $this->conn->delete($this->table, $conditions);
        $this->id = $id;
        return $result;
    }
}
?>
