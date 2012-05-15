<?php
/**
 * Abstraction for dababase implementaion.
 * This class define how the databases should be.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class Database extends Object {
    
    protected $config = array();
    protected $connection;
    protected $connected = false;
    protected $transactionStarted;
    protected $results;
    
    public function __construct($config = array()) {
        $this->config = $config;
        $this->errorTitle = "Database Error";
    }
	
    protected abstract function hasResult();
    protected abstract function fetchRow($results = null);
	
    public abstract function connect();
    public abstract function close();
    
    public abstract function query($sql);
    public abstract function fetch($sql = null);
    public abstract function fetchAll($sql = null);
    public abstract function schema($table);
    
    public abstract function read($table = null, $params = array());
    public abstract function count($table, $field = "*", $conditions = array());
	
    public abstract function insert($table, $data = array());
    public abstract function update($table, $data = array(), $conditions = array());
    public abstract function delete($table, $conditions = array());
    public abstract function insertedId();

    public abstract function beginTransaction();
    public abstract function commitTransaction();
    public abstract function rollbackTransaction();
}
?>