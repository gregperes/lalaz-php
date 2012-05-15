<?php
/**
 * This is the list of users view of the demo app saple.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
?>
<h2>CRUD sample!</h2>
<hr /><br />
<h3>List of Users</h3><br />
<?php if ($listUsers) { ?>
    <table border="1" width="100%">
        <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Email</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($listUsers as $key => $value) { ?>
            <tr>
                <td><?php echo $value["id"]; ?></td>
                <td><?php echo $value["name"]; ?></td>
                <td><?php echo $value["email"]; ?></td>
                <td>
                    <?php HtmlHelper::actionLink("user", "index", "Edit", "demo", array("id" => $value["id"])); ?>
                    &nbsp;|&nbsp;
                    <?php HtmlHelper::actionLink("user", "delete", "Delete", "demo", array("id" => $value["id"])); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    No records.
<?php } ?>
<br /><br />
<form name="manage-user-form" action="<?php echo UrlRouter::action("user", "index", "demo"); ?>" method="post">
    <fieldset title="User">
        <input type="hidden" name="id" value="<?php echo ArrayHelper::getValue("id", $currentUser); ?>" />
        <label>Name</label>
        <br />
        <input type="text" maxlength="50" name="name" id="name-input" value="<?php echo ArrayHelper::getValue("name", $currentUser); ?>" />
        <br />
        <label>Email</label>
        <br />
        <input type="text" maxlength="50" name="email" id="email-input" value="<?php echo ArrayHelper::getValue("email", $currentUser); ?>" />
        <br /><br />
        <?php HtmlHelper::actionLink("user", "index", "Cancel", "demo"); ?>&nbsp;
        <input type="submit" value="Save" />
    </fieldset>
</form>