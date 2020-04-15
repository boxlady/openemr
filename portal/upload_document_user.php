<?php
/**
 * Upload documents to OpenEMR from the patient portal
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @author    Julie Buurman
 * @copyright Copyright (c) 2020 Julie Buurman <boxlady@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("verify_session.php");
require_once("$srcdir/documents.php");
require_once($GLOBALS['fileroot'] . "/controllers/C_Document.class.php");
require_once("../interface/globals.php");
require_once("$srcdir/patient.inc");


$patient_id = $pid;
$query = "select id from categories where name = 'Onsite Portal'";
$result = sqlStatement($query);
$ID = sqlFetchArray($result);
$category_id = $ID['id'];


$allowed = array('pdf');
$ext = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
//Check file type
if (!in_array($ext, $allowed)) {
    die(xlt( 'error is not a valid file type - please upload a pdf file'));
}
//Checks for Executables
if (preg_match("/(.*)\.(php|php3|php4|php5|php7)$/i", $_FILES['fileToUpload']['name']) !== 0) {
    die(xlt('Executables not allowed'));
}
//Checks size
if ($_FILES["fileToUpload"]["size"] > 64000000) {
    die(xlt('Sorry, your file is too large.'));
}

if (!empty($_FILES)) {
    $name     = $_FILES['fileToUpload']['name'];
    $type     = $_FILES['fileToUpload']['type'];
    $tmp_name = $_FILES['fileToUpload']['tmp_name'];
    $size     = $_FILES['fileToUpload']['size'];
    $owner    = $GLOBALS['userauthorized'];

    $result = addNewDocument($name, $type, $tmp_name, $error, $size, $owner, $patient_id, $category_id);
    if ($result){
        echo(xlt('File was uploaded'));
    }else{
        echo(xlt('File failed'));
    }

}
?>
