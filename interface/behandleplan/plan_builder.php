<?php

require_once("../globals.php");


use OpenEMR\Core\Header;
use OpenEMR\OeUI\OemrUI;


if ($_POST['behandelplan_select']) {
    $behandelplan_id = 1;
    $behandelplan_id = $_POST['behandelplan_select'];
    $behandelplaninfo = sqlQuery('select * from behandleplanen where id = ?', array($behandelplan_id));
} else {
    $behandelplan_id = 1;
}
function getBehandplans($tmp = 0)
{
    $sql = 'Select * from behandleplanen';
    echo '<select onchange="plan_form.submit()" id="behandelplan_select" name="behandelplan_select" class=" select">';
    $result = sqlStatement($sql);
    foreach ($result as $item) {
        echo '<option ';
        if ($tmp != 0 && $tmp == $item['id']) {
            echo "selected";
        }
        echo ' value="' . $item['id'];
        echo '">' . $item['name'] . '</option>';
    }
    echo "</select>";
}

function makeRow($type, $naam, $doosering, $pathway, $updown, $merk, $LnName, $ingred, $uileg, $upmerking)
{
    /*                 <td class="#"><input type="checkbox" id="check_1"></td>
                <td class="Type">Supplement</td>
                <td class="Naam">Berberine</td>
                <td class="Dosering">500 mg</td>
                <td class="Pathway">AMPK</td>
                <td class="Up/Down">1</td>
                <td class="Merk">Vitals</td>
                <td class="LnName">2 x 1 cap</td>
                <td class="Contraindicaties">b.v. vitamine C</td>
                <td class="Ingredienten">dasd</td>
                <td class="Uitleg">Werk adssakldjsakl</td>
                <td class="Upmerking">asdas</td> */


}

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    Header::setupHeader(['datatables', 'datatables-dt', 'datatables-bs', 'report-helper']); ?>
    <script type="text/javascript">

        $(function () {
            $('#mymaintable').DataTable({
                stripeClasses: ['stripe1', 'stripe2'],
                orderClasses: false,
                <?php // Bring in the translations ?>
                <?php require($GLOBALS['srcdir'] . '/js/xl/datatables-net.js.php'); ?>
            });
        });

        function dopclick(behandelid, id, type) {
            top.restoreSession();
            if (type == "new") {
                dlgopen('add_edit_supp.php?behadelplanID=' + encodeURIComponent(behandelid) + '&thistype=' + encodeURIComponent(type), '_blank', 650, 500, '', <?php echo xlj("New"); ?>);
            } else {
                dlgopen('add_edit_supp.php?behadelplanID=' + encodeURIComponent(behandelid) + '&med_id=' + encodeURIComponent(id) + '&thistype=' + encodeURIComponent(type), '_blank', 650, 500, '', <?php echo xlj("Edit"); ?>);
            }
        }

        function newBehandelPlan(category, selector) {
            top.restoreSession();
            var id = "0";
            if (category == "edit") {
                var selector = document.getElementById('behandelplan_select');
                id = [selector.selectedIndex].value;
            }
            dlgopen('add_edit_BehandelPlan.php?id=' + encodeURIComponent(id) + '&selector=' + encodeURIComponent(selector), '_blank', 650, 500, '');
        }

        function setPlan(dbid, name) {
            opt = document.createElement("option", dbid);
            opt.value = dbid;
            opt.text = name;
            opt.selected = true;
            document.getElementById(behandelplan_select).appendChild(opt);
        }
    </script>
    <style>
        div.select_behandplan_row {
            width: 100%;
            height: 10em;
            display: flex;
            align-items: center;
            justify-content: center
        }

        a, a:visited, a:hover {
            color: var(--primary);
        }

        #mymaintable thead .sorting::before,
        #mymaintable thead .sorting_asc::before,
        #mymaintable thead .sorting_asc::after,
        #mymaintable thead .sorting_desc::before,
        #mymaintable thead .sorting_desc::after,
        #mymaintable thead .sorting::after {
            display: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            margin: 0 !important;
            border: 0 !important;
        }

        .paginate_button:hover {
            background: transparent !important;
        }

        .behandelplan_title {
            text-align: center;
            font-size: 1.1em;
        }

        .button_group {
            padding: 10px;
        }

    </style>

    <?php
    $arrOeUiSettings = array(
        'heading_title' => "behandleplan builder",
        'include_patient_name' => false,// use only in appropriate pages
        'expandable' => false,
        'expandable_files' => array("behandleplan_xpd"),//all file names need suffix _xpd
        'action' => "",//conceal, reveal, search, reset, link or back
        'action_title' => "Behandle plan",
        'action_href' => "",//only for actions - reset, link or back
        'show_help_icon' => false,
        'help_file_name' => "",
    );
    $oemr_ui = new OemrUI($arrOeUiSettings);
    ?>
    <title><?php
        echo xlt('Behandleplan builder') ?></title>

</head>
<body class="body_top">
<div id="container_div" class="<?php
echo attr($oemr_ui->oeContainer()); ?>">
    <div class="analyze_table_row">
        <div>Analyse</div>
        <table id="analyse_table" class="table">
            <thead>
            <tr>
                <th scope="col">Patways</th>
                <th scope="col">Diagnose</th>
                <th scope="col">Totaal</th>
                <th scope="col">Supplementen</th>
                <th scope="col">Medicijnen</th>
                <th scope="col">Infusen</th>
                <th scope="col">Overig</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="Patways"> Glucose</td>
                <td class="Diagnose"> -1</td>
                <td class="Totaal"> -1</td>
                <td class="Supplementen"> -1</td>
                <td class="Medicijnen"> -1</td>
                <td class="Infusen"> -1</td>
                <td class="Overig"> -1</td>
            </tr>
            <tr>
                <td class="Patways"> Glucose</td>
                <td class="Diagnose"> -1</td>
                <td class="Totaal"> -1</td>
                <td class="Supplementen"> -1</td>
                <td class="Medicijnen"> -1</td>
                <td class="Infusen"> -1</td>
                <td class="Overig"> -1</td>
            </tr>
            </tbody>
        </table>
    </div>
    <form id="plan_form" name="plan_form" method='post' action='plan_builder.php'>
        <input type="hidden" name="behandelplanID" value="<?php echo attr($behandelplan_id); ?>"/>

        <div class="row">
            <div class="select_behandplan_row center">
                <?php getBehandplans($behandelplaninfo['id']); ?>
                <div class="button_group">
                    <?php echo "<button class='btn btn-primary btn-sm btn-add mr-1' onclick='newBehandelPlan(0," . attr_js('new') . ")'>" . xlt('Toevoegen') . "</button>"; ?>
                    <button type="button" class="btn btn-sm  btn-secondary btn-save mr-1" id="save_">Opslaan</button>
                    <button type="button" class="btn btn-sm btn-delete btn-danger mr-1" id="del_">Verwijderen</button>
                </div>
            </div>
        </div>

        <!--        Change title based on Drop Down-->
        <div class="behandelplan_title">
            <p class="behandelplan_title"> Behandelplan: Breast-Cancer </p>
        </div>

        <div class="behandelplan_rows">
            <div class="supplement_row">

                <div>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-add mr-1' onclick='dopclick($behandelplan_id,0, " . attr_js('new') . ")'>" . xlt('New') . "</a>\n"; ?>
                    <?php echo "<a href='javascript:;' class='btn btn-primary btn-sm btn-add mr-1' onclick='dopclick(0, 0," . attr_js('edit') . ")'>" . xlt('From DB') . "</a>\n"; ?>
                    <button type="button" class="btn btn-sm btn-delete btn-danger mr-1" id="del_toBehandleplan">
                        Verwijderen
                    </button>
                </div>

                <table id="supplement_table" class="table">
                    <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="check_all"></th>
                        <th scope="col">Type</th>
                        <th scope="col">Naam</th>
                        <th scope="col">Dosering</th>
                        <th scope="col">Pathway</th>
                        <th scope="col">Up/Down</th>
                        <th scope="col">Merk</th>
                        <th scope="col">LnName</th>
                        <th scope="col">Contraindicaties</th>
                        <th scope="col">Ingredienten</th>
                        <th scope="col">Uitleg</th>
                        <th scope="col">Opmerking</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = sqlStatement("Select * from bhp_medicijnen where Behandelplan_id = ?", $behandelplan_id);
                    foreach ($sql as $row) {
                        echo '<tr>';
                        echo '<td class="edit">'."<a href='javascript:;' class='btn btn-primary btn-sm btn-add mr-1' onclick='dopclick($behandelplan_id,$row[id], " . attr_js('Edit') . ")'>" . xlt('Edit') . "</a></td>";
                        echo '<td class="Type">' . $row['type'] . "</a></td>";
                        echo '<td class="Naam">' . $row['name'] . "</a></td>";
                        echo '<td class="Dosering">' . $row['dosering_amount'] . ' ' . $row['dosering_unit'] . "</a></td>";
                        echo '<td class="Pathway">' . $row['pathway_01'] . "</a></td>";
                        echo '<td class="Up/Down">' . 'TODO' . "</a></td>";
                        echo '<td class="Merk">' . $row['merk'] . "</a></td>";
                        echo '<td class="LnName">' . $row['lnname'] . "</a></td>";
                        echo '<td class="Contraindicaties">' . $row['contraindicaties_01'] . "</a></td>";
                        echo '<td class="Ingredienten">' . $row['type'] . "</a></td>";
                        echo '<td class="Uitleg">' . $row['uitleg'] . "</a></td>";
                        echo '<td class="Upmerking">' . $row['upmerking'] . "</a></td>";

                    }

                    ?>

                    </tbody>
                </table>
            </div>
    </form>

</div><!--End of div container -->
<?php
$oemr_ui->oeBelowContainerDiv(); ?>
</body>
</html>
