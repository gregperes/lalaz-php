<?php
/**
 * This is the index user action for the demo app sample.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Index extends Action {
    
    public function execute($view) {
        $user = new User();
        $id = $this->getRequestData("id");
        $currentUser = null;
        
        if ($_POST) {
            if (empty($id)) {
                $user->insert(array(
                    "name" => $this->getRequestData("name"),
                    "email" => $this->getRequestData("email")));
            } else {
                $user->update($id, array(
                    "name" => $this->getRequestData("name"),
                    "email" => $this->getRequestData("email")));
            }
        } else {
            if (!empty($id)) {
                $currentUser = $user->first(array("id" => $id));
            }
        }
        
        $templateFile = App::path(array("demo", "template.php"), APP_VIEWS_PATH);
        $listUsers = $user->all();
        
        $view->setData("listUsers", $listUsers);
        $view->setData("currentUser", $currentUser);
        $view->renderAction($templateFile);
    }
}
?>