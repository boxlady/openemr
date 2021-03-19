<?php

/**
 * add or edit supplement/Med/ Etc
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
require_once '../globals.php';
require_once $GLOBALS['srcdir'] . '/lists.inc';
require_once $GLOBALS['srcdir'] . '/options.inc.php';
require_once $GLOBALS['fileroot'] . '/custom/code_types.inc.php';
require_once $GLOBALS['srcdir'] . '/csv_like_join.php';

use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;


$info_msg = "";
$irow = array();
$type_index = 0;
$code_texts = array();

$BehandelplanID = $_GET['behadelplanID'];
$thistype = $_GET['thistype'];
if ($_GET['med_id'] !== "0") {
    $id = 0;
    $id = $_GET['med_id'];
    $irow = sqlQuery("Select * from bhp_medicijnen where id = ?", array($id));
    echo $irow;
} else {
    $id = 0;
}

function get_supplemts()
{
    $sqls = "Select bhp_medicijnen.name, bhp_medicijnen.dosering_amount, bhp_dosering.units, bhp_pathway.name,
       bhp_pathway.`up/down`, bhp_medicijnen.merk, bhp_medicijnen.lnname, bhp_ingredienten.name,
       bhp_ingredienten.extranal_info, bhp_medicijnen.Uitleg, bhp_medicijnen.Opmerking
    from bhp_medicijnen
    inner join bhp_dosering on bhp_medicijnen.dosering_unit = bhp_dosering.id
    inner join bhp_pathway on bhp_medicijnen.pathway = bhp_pathway.id
    inner join bhp_ingredienten on bhp_medicijnen.ingredienten = bhp_ingredienten.id";
    return sqlStatement($sqls);
}

function get_units($id = 0)
{
    $sql = 'Select * from bhp_dosering';
    echo '<select name="dosering_units">';
    $result = sqlStatement($sql);
    foreach ($result as $item) {
        echo '<option value="' . $item['id'];
        if ($id != 0 && $id == $item['id']) {
            echo " selected = 'selected'";
        }
        echo '">' . $item['units'] . '</option>';
    }
    echo "</select>";
}

function get_pathways($id = 0)
{
    $sql = 'Select * from bhp_pathway';
    echo '<select id="pathways_select_01" name="pathways_select_01" class="pathways_select select">';
    $result = sqlStatement($sql);
    foreach ($result as $item) {
        echo '<option value="' . $item['id'];
        if ($id != 0 && $id == $item['id']) {
            echo " selected = 'selected'";
        }
        echo '">' . $item['name'] . " " . $item['up/down'] . '</option>';
    }
    echo "</select>";

}

function get_ingredienten($id = 0)
{
    $sql = 'Select * from bhp_ingredienten';
    echo '<select id="ingred_select_01" name="ingred_select_01" class="ingred_select select">';
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

function get_Contraindicaties($id = 0)
{
    $sql = 'Select * from bhp_contraindicaties';
    echo '<select id="contrain_select_01" name="contrain_select_01" class="contrain_select select">';
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
    <?php
    Header::setupHeader(['common', 'datetime-picker', ' opener']); ?>
    <title><?php
        echo ($type) ? xlt('Edit Med') : xlt('Add new Med'); ?></title>

    <style>
        div.section {
            border: 1px solid var(--primary) !important;
            margin: 0 0 0 13px;
            padding: 7px;
        }

        div {
            padding: 1px;
        }

        /* Override theme's selected tab top color so it matches tab contents. */
        ul.tabNav li.current a {
            background: var(--white);
        }

        #dosering, #pathway, #ingredienten, #Contraindicaties {
            display: flex;

        }

        .column {
            flex: 45%;
        }

        .select {
            width: 150px;
        }

    </style>
    <script>
        ing_count = 1;
        path_count = 1;
        contra_count = 1;

        function refreshme() {
            debugger;
            document.forms[0].submit();
        }


        //open ingrents
        function ingredientenclick(category, selector) {
            top.restoreSession();
            var id = "0";
            if (category == "edit") {
                var selector = document.getElementById('ingred_select_' + selector);
                id = selector[selector.selectedIndex].value;
            }
            dlgopen('add_edit_ingredienten.php?id=' + encodeURIComponent(id) + '&selector=' + encodeURIComponent(selector), '_blank', 650, 500, '');
        }

        function setIngredient(dbid, name, selector) {
            opt = document.createElement("option", dbid);
            opt.value = dbid;
            opt.text = name;
            opt.selected = true;
            selector_id = 'ingred_select_0' + selector;
            document.getElementById(selector_id).appendChild(opt);
        }

        function add_more_ing() {
            //debugger;
            ing_count++;
            let select_id = 'ingred_select_0' + ing_count;
            let cln = document.getElementsByClassName("ing-list")[0].cloneNode(true);
            cln.id = 'ing_list_' + ing_count;
            cln.className = 'cloned_ing_list'
            cln.children[0].id = select_id;
            cln.children[0].name = select_id;
            document.getElementById("ingredienten_01").before(cln);
            let button = document.createElement("BUTTON");
            button.type = 'button';
            button.onclick = function () {
                $(this).parent().remove();
                return false;
            }
            button.innerHTML = '-';
            cln.appendChild(button).firstChild;
            //cln.insertAdjacentHTML('afterbegin', `<input type="button" class="btn btn-primary btn-sm btn-add mr-1" value="-" onclick='remove_ing(this.cln.attr('id'))'>`);
        }

        function pathwaysclick(category, selector) {
            top.restoreSession();
            var id = "0";
            if (category == "edit") {
                var selector = document.getElementById('pathways_select_' + selector);
                id = selector[selector.selectedIndex].value;
            }
            dlgopen('add_edit_pathway.php?id=' + encodeURIComponent(id) + '&selector=' + encodeURIComponent(selector), '_blank', 650, 500, '');
        }

        function setPathways(dbid, name, selector) {
            opt = document.createElement("option", dbid);
            opt.value = dbid;
            opt.text = name;
            opt.selected = true;
            selector_id = 'pathways_select_0' + selector;
            document.getElementById(selector_id).appendChild(opt);
        }

        function add_more_path() {
            path_count++;
            let select_id = 'pathway_select_0' + path_count;
            let cln = document.getElementsByClassName("path-list")[0].cloneNode(true);
            cln.id = 'path_list_' + path_count;
            cln.className = 'cloned_path_list'
            cln.children[0].id = select_id;
            cln.children[0].name = select_id;
            document.getElementById("pathways_01").before(cln);
            let button = document.createElement("BUTTON");
            button.type = 'button';
            button.onclick = function () {
                $(this).parent().remove();
                return false;
            }
            button.innerHTML = '-';
            cln.appendChild(button).firstChild;
        }

        function contraindicatiesclick(category, selector) {
            top.restoreSession();
            var id = "0";
            if (category == "edit") {
                var selector = document.getElementById('contrain_select_');
                id = selector[selector.selectedIndex].value;
            }
            dlgopen('add_edit_contraindicaties.php?id=' + encodeURIComponent(id) + '&selector=' + encodeURIComponent(selector), '_blank', 650, 500, '');
        }

        function setContra(dbid, name, selector) {
            opt = document.createElement("option", dbid);
            opt.value = dbid;
            opt.text = name;
            opt.selected = true;
            selector_id = 'contrain_select_0' + selector;
            document.getElementById(selector_id).appendChild(opt);
        }

        function add_more_contra() {
            contra_count++;
            let select_id = 'contrain_select_0' + path_count;
            let cln = document.getElementsByClassName("path-list")[0].cloneNode(true);
            cln.id = 'contra-list_' + path_count;
            cln.className = 'cloned_path_list'
            cln.children[0].id = select_id;
            cln.children[0].name = select_id;
            document.getElementById("contraredienten_01").before(cln);
            let button = document.createElement("BUTTON");
            button.type = 'button';
            button.onclick = function () {
                $(this).parent().remove();
                return false;
            }
            button.innerHTML = '-';
            cln.appendChild(button).firstChild;
        }

        function save(selector, id) {
            $.ajax({
                dataType: 'text',
                async: false,
                type: $(this).attr("method"),
                beforeSend: top.restoreSession,
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
                    opener.top.restoreSession();
                    opener.document.forms[0].submit();
                    dlgclose();

                },
                error: function (jqXHR, status, err) {
                    console.log('Error: ' + status + ' ' + err)
                    dlgclose();
                }
            }).responseText;
        }
    </script>
</head>

<body>
<div class="tabContainer">
    <form action="bh_close.php" class="form-horizontal" name='theform' method="post">
        <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>"/>
        <input type="hidden" name="behandelplanID" value="<?php echo attr($BehandelplanID); ?>"/>
        <div class="form-group">
            <label class="col-form-label"><?php
                echo xlt('Type'); ?>:</label>
            <select class="form-control" id="med_type" name="med_type">
                <option value="Supplementen">Supplementen</option>
                <option value="Medicijnen">Medicijnen</option>
                <option value="Infusen">Infusen</option>
                <option value="Overig">Overig</option>
                <option value="Anders">Anders</option>
            </select>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="form_title"><?php
                echo xlt('Naam'); ?>:</label>
            <input type='text' class="form-control" name='form_title' id='form_title' value='<?php
            echo attr($irow['name']) ?>'/>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="dosering"><?php
                echo xlt('Dosering'); ?>:</label>
            <div class="form-group" id='dosering'>
                <div class="column">
                    <input type='text' class="form-control" name='dosering_amount' id='dosering_amount'
                           value='<?php
                           echo attr($irow['dosering_amount']) ?>'/>
                </div>
                <div class="column">
                    <?php get_units($irow['dosering_unit']); ?>
                </div>
            </div>
        </div>
        <div class="form-group" id="path_div">
            <label class="col-form-label" for="pathways_01"><?php
                echo xlt('Pathways'); ?>:</label>
            <div id='pathways_01'>
                <div id="path-list" class="path-list column">
                    <?php get_pathways($irow['pathway_01']); ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-edit mr-1' onclick='pathwaysclick(" . attr_js('edit') . ',' . attr_js('01') . ")'>" . xlt('edit') . "</a>\n"; ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-add  mr-1' onclick='pathwaysclick(" . attr_js('new') . ',' . attr_js('01') . ")'>" . xlt('new') . "</a>\n"; ?>
                </div>
            </div>
            <div class="column">
                <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-duplicate mr-1' onclick='add_more_path(); return false'>" . xlt('More') . "</a>\n"; ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="merk"><?php echo xlt('Merk'); ?>:</label>
            <input type='text' class="form-control" name='merk' id='merk' value='<?php echo attr($irow['Merk']) ?>'/>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="lnname"><?php
                echo xlt('lnname'); ?>:</label>
            <input type='text' class="form-control" name='lnname' id='lnname' value='<?php
            echo attr($irow['lnname']) ?>'/>
        </div>
        <div class="form-group" id="ing_div">
            <label class="col-form-label" for="ingredienten_01"><?php
                echo xlt('Ingredienten'); ?>:</label>
            <div id='ingredienten_01'>
                <div id="ing-list" class="ing-list column">
                    <?php get_ingredienten($irow['ingredienten_01']); ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-edit mr-1' onclick='ingredientenclick(" . attr_js('edit') . ',' . attr_js('01') . ")'>" . xlt('edit') . "</a>\n"; ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-add mr-1' onclick='ingredientenclick(" . attr_js('new') . ',' . attr_js('01') . ")'>" . xlt('new') . "</a>\n"; ?>

                </div>
            </div>
            <div class="column">
                <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-duplicate mr-1' onclick='add_more_ing(); return false'>" . xlt('More') . "</a>\n"; ?>

            </div>
        </div>
        <div class="form-group" id="contra_div">
            <label class="col-form-label" for="Contraindicaties_01"><?php
                echo xlt('Contraindicaties'); ?>:</label>
            <div id='contraredienten_01'>
                <div id="contra-list" class="contra-list column">
                    <?php get_Contraindicaties(); ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-edit mr-1' onclick='contraindicatiesclick(" . attr_js('edit') . ',' . attr_js('01') . ")'>" . xlt('edit') . "</a>\n"; ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-add mr-1' onclick='contraindicatiesclick(" . attr_js('new') . ',' . attr_js('01') . ")'>" . xlt('new') . "</a>\n"; ?>
                </div>
            </div>
            <div class="column">
                <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-duplicate mr-1' onclick='add_more_contra(); return false'>" . xlt('More') . "</a>\n"; ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="uitleg"><?php echo xlt('Uitleg'); ?>:</label>
            <input type='text' class="form-control" name='uitleg' id='uitleg' value='<?php echo attr($irow['uitleg']) ?>'/>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="opmerking"><?php echo xlt('Opmerking'); ?>:</label>
            <input type='text' class="form-control" name='opmerking' id='opmerking' value='<?php echo attr($irow['opmerking']) ?>'/>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary btn-save" name='form_save'
                    value='<?php echo $id ? xla('Update') : xla('Add'); ?>'><?php echo $id ? xla('Update') : xla('Add'); ?></button>
            <?php if ($id != 0) { ?>
                <button class="btn btn-danger" type='submit' name='form_delete' value='<?php echo xla('Delete'); ?>'><?php echo xla('Delete'); ?></button>
            <?php } ?>
            <button type='button' class="btn btn-secondary btn-cancel" onclick='dlgclose()'><?php echo xla('Cancel'); ?></button>

        </div>
    </form>
</div>


<?php
validateUsingPageRules($_SERVER['PHP_SELF']); ?>


</body>

</html>
