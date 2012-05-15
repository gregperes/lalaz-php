<?php
/**
 * This is the index action for the demo app sample.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Index extends Action {
    
    public function execute($view) {
        $templateFile = App::path(array("demo", "template.php"), APP_VIEWS_PATH);
        $view->renderAction($templateFile);
    }
}
?>