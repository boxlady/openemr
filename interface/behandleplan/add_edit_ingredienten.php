<?php
require_once("../globals.php");


use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;


$selector = $_GET['selector'];
if ($_GET['id'] !== "0") {
    $id = 0;
    $id = $_GET['id'];
    $Ing_Info = sqlQuery("Select id, name, notes, extranal_info from bhp_ingredienten where id = ?", array($id));
} else {
    $id = 0;
}

?>
<html>
<head>
    <title></title>

    <?php Header::setupHeader(['common', "opener"]); ?>

</head>
<body class="body_top">
<form id="add_edit_ingredienten_form">
    <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>"/>
    <input type="hidden" id="id_ing" name="id" value="<?php echo attr($id); ?>"/>
    <div class="center">
        <div class="form-group">
            <label class="col-form-label" for="ing_name"><?php echo xlt('Naam'); ?>:</label>
            <input type="text" name="ing_name" id="ing_name" value="<?php echo $Ing_Info['name'] ?>">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="form_notes"><?php echo xlt('notes'); ?>:</label>
            <input type="text" name="form_notes" id="form_notes" class="form-control" value="<?php echo $Ing_Info['notes'] ?>">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="form_extranal_info"><?php echo xlt('external_info'); ?>:</label>
            <input type="text" name="form_extranal_info" id="form_extranal_info" class="form-control" value="<?php echo $Ing_Info['extranal_info'] ?>">
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

        sname = document.getElementById('ing_name').value;
        notes = document.getElementById('form_notes').value;
        external_info = document.getElementById('form_extranal_info').value;
        $.ajax({
            dataType: 'text',
            async: false,
            type: $(this).attr("method"),
            beforeSend: top.restoreSession,
            url: 'save_db_ajax.php', // the url we want to send and get data from
            data: {
                action: 'save',
                type: 'ing',
                db_id: id,
                csrf_token_form: "<?php echo attr(CsrfUtils::collectCsrfToken()); ?>",
                sname: sname,
                notes: notes,
                external_info: external_info,
            },
            success: function (response) {
                console.log(response)
                var res = JSON.parse(response)
                setIng(res.dbid, res.name, selector);
                dlgclose();
            },
            error: function (jqXHR, status, err) {
                console.log('Error: ' + status + ' ' + err)

                dlgclose();
            }
        }).responseText;
    }

    function update(selector, id) {
        sname = document.getElementById('ing_name').value;
        notes = document.getElementById('form_notes').value;
        external_info = document.getElementById('form_extranal_info').value;
        $.ajax({
            dataType: 'text',
            async: false,
            type: $(this).attr("method"),
            beforeSend: top.restoreSession,
            url: 'save_db_ajax.php', // the url we want to send and get data from
            data: {
                action: 'update',
                type: 'ing',
                db_id: id,
                csrf_token_form: "<?php echo attr(CsrfUtils::collectCsrfToken()); ?>",
                sname: sname,
                notes: notes,
                external_info: external_info,
            },
            success: function (response) {
                console.log(response)
                var res = JSON.parse(response)
                setIng(res.dbid, res.name, selector);
                dlgclose();
            },
            error: function (jqXHR, status, err) {
                console.log('Error: ' + status + ' ' + err)
                dlgclose();
            }
        }).responseText;
    }

    function setIng(dbid, name, selector) {
        debugger;
        if (opener.closed || !opener.setIngredient) {
            alert("<?php echo htmlspecialchars(xl('The destination form was closed; I cannot act on your selection.'), ENT_QUOTES); ?>");
        } else {
            opener.setIngredient(dbid, name, selector);
            dlgclose();
            return false;

        }
    }


</script>
</html>
