<?php
/**
 * Abstraction for Action.
 * The Action Class has only one method, 
 * reducing class size, gaining maintanance 
 * instead of having a Controller Class with several methods of control
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class Action extends Object {
	
    private $requestData = array();

    protected function getRequestData($key) {
        return ArrayHelper::getValue($key, $this->requestData);
    }

    public function __construct($requestData = array()) {
        $this->errorTitle = "Action Error";
        $this->requestData = $requestData;
    }

    public function isAjaxRequest(){
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public abstract function execute($view);
}
?>