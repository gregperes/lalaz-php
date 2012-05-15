<?php
/**
 * This is the index view of the demo app saple.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
?>
<h2>Welcome to Lalaz PHP Framework</h2>
<p>
    This a sample application that demonstrates the use of the framework.
</p>
<p>
    <?php HtmlHelper::actionLink("user", "index", "See a simple CRUD using the Lalaz Framework!", "demo"); ?>
</p>