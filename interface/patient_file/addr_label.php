<?php

/**
* interface/patient_file/addr_label.php Displaying a PDF file of Labels for printing.
*
* Program for displaying Address Labels
*
* @package   OpenEMR
* @link      http://www.open-emr.org
* @author    Terry Hill <terry@lillysystems.com>
* @author    Daniel Pflieger <growlingflea@gmail.com>
* @copyright Copyright (c) 2014 Terry Hill <terry@lillysystems.com>
* @copyright Copyright (c) 2017 Daniel Pflieger <growlingflea@gmail.com>
* @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
*/

require_once("../globals.php");

//Get the data to place on labels
//

$patdata = sqlQuery("SELECT " .
  "p.fname, p.mname, p.lname, p.pubpid, p.DOB, " .
  "p.street, p.city, p.state, p.postal_code, p.pid, p.email, p.phone_cell  " .
  "FROM patient_data AS p " .
  "WHERE p.pid = ? LIMIT 1", array($pid));

// re-order the dates
//
$today = oeFormatShortDate($date = 'today');
$dob = oeFormatShortDate($patdata['DOB']);

//get label type and number of labels on sheet
//

if ($GLOBALS['chart_label_type'] == '1') {
    $pdf = new PDF_Label('5160');
    $last = 30;
}

if ($GLOBALS['chart_label_type'] == '2') {
    $pdf = new PDF_Label('5161');
    $last = 20;
}

if ($GLOBALS['chart_label_type'] == '3') {
    $pdf = new PDF_Label('5162');
    $last = 1;
}
if ($GLOBALS['chart_label_type'] == '4') {
    $pdf = new PDF_Label('fvp10p');
    $last = 1;
}
if ($GLOBALS['chart_label_type'] == '5') {
    $pdf = new PDF_Label('fvp10a');
    $last = 1;
}

$pdf->AddPage();
$exmp = "";
$exmp .= $patdata['fname'] .' '.$patdata['mname'] .' '.$patdata['lname']. ' '.'(PID-'.$patdata['pid'].')' . "\n";
$exmp .= $patdata['street'] ."\n";
$exmp .= $patdata['postal_code'] .' '.$patdata['city']."\n";
// Added spaces to the sprintf for Fire Fox it was having a problem with alignment
//$text = sprint($exmp, $dob, $today, $patdata['email']);

// For loop for printing the labels
//

for ($i=1; $i<=$last; $i++) {
    $pdf->Add_Label($exmp);
}

$pdf->Output();
