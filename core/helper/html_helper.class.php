<?php
/**
 * Helper methods for HTML.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class HtmlHelper extends Object {
    
    public static function actionLink($directory, $actionName, $text, $prefix = null, $params = array()) {
        $url = UrlRouter::action($directory, $actionName, $prefix, $params);
        echo "<a href=\"$url\">$text</a>";
    }
}
?>