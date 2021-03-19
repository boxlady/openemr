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

$BehandelplanID= $_POST['behandelplanID'];

if ($_POST['form_save'] == 'Add') {
    if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
        CsrfUtils::csrfNotVerified();
    }
    $sup_name = isset($_POST['form_title']) ? $_POST['form_title'] : '';
    $med_type = isset($_POST['med_type']) ? $_POST['med_type'] : '';
    $dosering_amount = isset($_POST['dosering_amount']) ? $_POST['dosering_amount'] : '';
    $dosering_unit = isset($_POST['dosering_units']) ? $_POST['dosering_units'] : '';
    $merk = isset($_POST['merk']) ? $_POST['merk'] : '';
    $lname = isset($_POST['lnname']) ? $_POST['lnname'] : '';
    $uitleg = isset($_POST['uitleg']) ? $_POST['uitleg'] : '';
    $upmerking = isset($_POST['opmerking']) ? $_POST['opmerking'] : '';

    $pathway_01 = $_POST['pathways_select_01'];
    $pathway_02 = $_POST['pathways_select_02'];
    $pathway_03 = $_POST['pathways_select_03'];
    $pathway_04 = $_POST['pathways_select_04'];
    $pathway_05 = $_POST['pathways_select_05'];

    $ing_01 = $_POST['ingred_select_01'];
    $ing_02 = $_POST['ingred_select_02'];
    $ing_03 = $_POST['ingred_select_03'];
    $ing_04 = $_POST['ingred_select_04'];
    $ing_05 = $_POST['ingred_select_05'];

    $contra_01 = $_POST['contrain_select_01'];
    $contra_02 = $_POST['contrain_select_02'];
    $contra_03 = $_POST['contrain_select_03'];
    $contra_04 = $_POST['contrain_select_04'];
    $contra_05 = $_POST['contrain_select_05'];

    $dbid = sqlInsert("Insert into `bhp_medicijnen` (`name`, `type`, `dosering_amount`, dosering_unit,
merk, lnname, uitleg, upmerking,
pathway_01,pathway_02,pathway_03,pathway_04,pathway_05,
ingredienten_01, ingredienten_02, ingredienten_03, ingredienten_04, ingredienten_05,
contraindicaties_01,contraindicaties_02,contraindicaties_03,contraindicaties_04,contraindicaties_05, Behandelplan_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ",
        array($sup_name, $med_type, $dosering_amount, $dosering_unit, $merk, $lname, $uitleg, $upmerking,
            $pathway_01, $pathway_02, $pathway_03, $pathway_04, $pathway_05,
            $ing_01, $ing_02, $ing_03, $ing_04, $ing_05,
            $contra_01, $contra_02, $contra_03, $contra_04, $contra_05, $BehandelplanID
        ));
    echo '
<script type="text/javascript>
    self.close();
</script>';


}
if ($_POST['form_save'] == 'Update') {
    if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
        CsrfUtils::csrfNotVerified();
    }

}

if ($_POST['form_save'] == 'Delete') {
    if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
        CsrfUtils::csrfNotVerified();
    }

}
?>


<html>

<head>
    <script type='text/javascript'>
       this.dlgclose();
    </script>
</head>
<body>
</body>
</html>
