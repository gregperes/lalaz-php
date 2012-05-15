<?php
/**
 * Represents the reponse in JSON.
 *
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class JsonResponse extends Object {

    public $hasError = false;
    public $message = "";
    public $data = null;

    protected function  __construct() {}

    public static function errorResponse($message) {
        $self = new JsonResponse();
        $self->hasError = true;
        $self->message = $message;
        $self->data = null;
        return $self;
    }

    public static function successResponse($data) {
        $self = new JsonResponse();
        $self->hasError = false;
        $self->message = "Success!";
        $self->data = $data;
        return $self;
    }
}
?>