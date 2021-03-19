<?php
require_once("../globals.php");


use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;


$selector = $_GET['selector'];
if ($_GET['id'] !== "0") {
    $id = 0;
    $id = $_GET['id'];
    $path_Info = sqlQuery("Select id, name, ing_id, reaction from bhp_contraindicaties where id = $id");
} else {
    $id = 0;
}
function get_ingredienten($id = 0)
{
    $sql = 'Select * from bhp_ingredienten';
    echo '<select id="ingred_select_01" name="ingred_select" class="ingred_select select">';
    $result = sqlStatement($sql);
    foreach ($result as $item) {
        echo '<option value="' . $item['id'];
        if ($id != 0 && $id == $item['id']) {
            echo " selected = 'selected'";
        }
        echo '">' . $item['name'] . '</option>';
    }
    echo "</select>";

}

?>
<html>
<head>
    <title></title>

    <?php Header::setupHeader(['common', "opener"]); ?>

</head>
<body class="body_top">
<form id="add_edit_pathways_form">
    <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>"/>
    <input type="hidden" id="id_path" name="id" value="<?php echo attr($id); ?>"/>
    <div class="center">
        <div class="form-group">
            <label class="col-form-label" for="path_name"><?php echo xlt('Naam'); ?>:</label>
            <input type="text" name="path_name" id="path_name" value="<?php echo $path_Info['name'] ?>">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="ingred_select_01"><?php echo xlt('Ingredient'); ?>:</label>
            <?php get_ingredienten($path_Info['ing-id'] = 0); ?>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="path_reaction"><?php echo xlt('Reaction'); ?>:</label>
            <input type="text" name="path_reaction" id="path_reaction" value="<?php echo $path_Info['reaction'] ?>">
        </div>
    </div>
    <div class="btn-group">
        <button onclick="save(<?php echo $selector . "," . $id ?>)" class="btn btn-primary btn-save" name='form_save'
                value='<?php echo $id ? xla('Update') : xla('Add'); ?>'><?php echo $id ? xla('Update') : xla('Add'); ?></button>
        <?php if ($id != 0) { ?>
            <button class="btn btn-danger" type='submit' name='form_delete' value='<?php echo xla('Delete'); ?>'><?php echo xla('Delete'); ?></button>
        <?php } ?>
        <button type='button' class="btn btn-secondary btn-cancel" onclick='window.close()'><?php echo xla('Cancel'); ?></button>
    </div>
</body>
<script>
    function save(selector, id) {
        sname = document.getElementById('path_name').value;
        notes = document.getElementById('path_reaction').value;
        ing_id = document.getElementById('ingred_select_01').value;
        $.ajax({
            dataType: 'text',
            async: false,
            type: $(this).attr("method"),
            beforeSend: top.restoreSession,
            url: 'save_db_ajax.php', // the url we want to send and get data from
            data: {
                action: 'save',
                type: 'contra',
                db_id: id,
                csrf_token_form: "<?php echo attr(CsrfUtils::collectCsrfToken()); ?>",
                sname: sname,
                notes: notes,
                ing_id:ing_id,
            },
            success: function (response) {
                console.log(response)
                var res = JSON.parse(response)
                setpath(res.dbid, res.name, selector);
                dlgclose();
            },
            error: function (jqXHR, status, err) {
                console.log('Error: ' + status + ' ' + err)
                dlgclose();
            }
        }).responseText;
    }

    function update(selector, id) {
        sname = document.getElementById('path_name').value;
        notes = document.getElementById('form_notes').value;
        $.ajax({
            dataType: 'text',
            async: false,
            type: $(this).attr("method"),
            beforeSend: top.restoreSession,
            url: 'save_db_ajax.php', // the url we want to send and get data from
            data: {
                action: 'update',
                type: 'path',
                db_id: id,
                csrf_token_form: "<?php echo attr(CsrfUtils::collectCsrfToken()); ?>",
                sname: sname,
                notes: notes,
            },
            success: function (response) {
                console.log(response)
                var res = JSON.parse(response)
                setpath(res.dbid, res.name, selector);
                dlgclose();
            },
            error: function (jqXHR, status, err) {
                console.log('Error: ' + status + ' ' + err)
                dlgclose();
            }
        }).responseText;
    }

    function setpath(dbid, name, selector) {
        if (opener.closed || !opener.setContra) {
            alert("<?php echo htmlspecialchars(xl('The destination form was closed; I cannot act on your selection.'), ENT_QUOTES); ?>");
        } else {
            opener.setContra(dbid, name, selector);
            dlgclose();
            return false;

        }
    }


</script>
</html>
