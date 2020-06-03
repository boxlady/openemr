<?php

/**
 * Sports Physical Form
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Jason Morrill
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2009 Jason Morrill
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

/** CHANGE THIS - name of the database table associated with this form **/
$table_name = "form_example";

/** CHANGE THIS name to the name of your form **/
$form_name = "My Example Form";

/** CHANGE THIS to match the folder you created for this form **/
$form_folder = "example";

formHeader("Form: " . $form_name);
$returnurl = 'encounter_top.php';

/* load the saved record */
$record = formFetch($table_name, $_GET["id"]);

/* remove the time-of-day from the date fields */
if ($record['form_date'] != "") {
    $dateparts = explode(" ", $record['form_date']);
    $record['form_date'] = $dateparts[0];
}

if ($record['dob'] != "") {
    $dateparts = explode(" ", $record['dob']);
    $record['dob'] = $dateparts[0];
}

if ($record['sig_date'] != "") {
    $dateparts = explode(" ", $record['sig_date']);
    $record['sig_date'] = $dateparts[0];
}
?>

<html><head>

<?php Header::setupHeader(); ?>

<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css">

</head>

<body class="body_top">

Printed on <?php echo date("F d, Y", time()); ?>

<form method=post action="">
<input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>" />

<span class="title"><?php echo xlt($form_name); ?></span><br />

<!-- container for the main body of the form -->
<div id="print_form_container">

<div id="print_general">
<table>
<tr><td>
Date:
   <input type='text' size='10' name='form_date' id='form_date'
    value='<?php echo attr($record['form_date']);?>'
    title='<?php echo xla('yyyy-mm-dd'); ?>'
    />
</td></tr>
<tr><td>
Name: <input id="name" name="name" type="text" size="50" maxlength="250" value="<?php echo attr($record['name']);?>">
Date of Birth:
   <input type='text' size='10' name='dob' id='dob'
    value='<?php echo attr($record['dob']);?>'
    title='<?php echo xla('yyyy-mm-dd Date of Birth'); ?>'
    />
</td></tr>
<tr><td>
Phone: <input name="phone" id="phone" type="text" size="15" maxlength="15" value="<?php echo attr($record['phone']);?>">
</td></tr>
<tr><td>
Address: <input name="address" id="address" type="text" size="80" maxlength="250" value="<?php echo attr($record['address']);?>">
</td></tr>
</table>
</div>

<div id="print_bottom">
Use this space to express notes <br />
<textarea name="notes" id="notes" cols="80" rows="4"><?php echo attr($record['notes']);?></textarea>
<br /><br />
<div style="text-align:right;">
Signature?
<input type="radio" id="sig" name="sig" value="y" <?php if ($record["sig"] == 'y') {
    echo "CHECKED";
                                                  } ?>>Yes
/
<input type="radio" id="sig" name="sig" value="n" <?php if ($record["sig"] == 'n') {
    echo "CHECKED";
                                                  } ?>>No
&nbsp;&nbsp;
Date of signature:
   <input type='text' size='10' name='sig_date' id='sig_date'
    value='<?php echo attr($record['sig_date']);?>'
    title='<?php echo xla('yyyy-mm-dd'); ?>' />
</div>
</div>

</div> <!-- end form_container -->

</form>

</body>

<script language="javascript">
window.print();
window.close();
</script>

</html>
