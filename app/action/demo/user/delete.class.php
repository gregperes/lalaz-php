<?php
/**
 * This is the delete user action for the demo app sample.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Delete extends Action {
    
    public function execute($view) {
        $id = $this->getRequestData("id");
        
        if (!empty($id)) {
            $user = new User();
            $user->delete($id);
        }
        
        UrlRouter::redirectToAction("user", "index", "demo");
    }
}
?>