<?php


require_once(__DIR__ . '/../../globals.php');
require_once($GLOBALS['srcdir'] . '/calendar.inc');
require_once("Email_Controller.php");


use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;


if (!AclMain::aclCheckCore('patients', 'appt', '', array('write', 'wsome'))) {
    die(xl('Access not allowed'));
}
$email_controller = new Email_Controller();
$pt_cat = sqlStatement("Select * from openemr_postcalendar_categories where pc_cattype = 0");

if ($_GET['download_file'] == 1) {
    if (!CsrfUtils::verifyCsrfToken($_GET["csrf_token_form"])) {
        CsrfUtils::csrfNotVerified();
    }

    $target_file = $email_controller->get_target_file();
    if (!file_exists($target_file)) {
        echo xlt('file missing');
    } else {
        header('HTTP/1.1 200 OK');
        header('Cache-Control: no-cache, must-revalidate');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=holiday.csv");
        readfile($target_file);
        exit;
    }

    die();
}
if (!empty($_POST['bn_upload'])) {
    if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
        CsrfUtils::csrfNotVerified();
    }
    if ($_FILES['name'] == array()) {
        $email_controller->clear_attachment($_POST['category_selected']);
    }
    //Upload and save the file attachment
    $saved = $email_controller->upload_file($_FILES);
    $csv_file_data = '';
    if ($saved) {
        $csv_file_data = $email_controller->get_file_csv_data();
        $email_controller->save_attachment($_POST['category_selected'], $_FILES);
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php Header::setupHeader(['common', 'opener']); ?>
    <title><?php echo xlt('Email Settings') ?></title>
    <style>
        body {
            overflow-x: hidden;
        }
        #file_date{
            text-align: right;
        }

        @media (max-width: 768px) {
            body {
                margin-bottom: 15px;
            }
        }
    </style>
    <script>
        function onChange() {
            var sel = document.getElementById("category_selected");
            var selected = sel.options[sel.selectedIndex];
            var extra = selected.getAttribute('extra');
            if (extra != '') {
                document.getElementById("filename").innerHTML = "File: " + extra;
            }


        }
    </script>
</head>
<body class="body_top">
<?php
if ($saved) {
    echo "<p style='color:green'>" .
        xlt('Successfully Completed');
    "</p>\n";
} elseif (
    !empty($_POST['bn_upload']) &&
    !empty($_POST['category_selected']) &&
    !empty($_POST['sync'])
) {
    echo "<p style='color:red'>" .
        xlt('Operation Failed');
    "</p>\n";
} ?>
<div class="container-fluid">
    <form method="post" action="email_settings.php" enctype="multipart/form-data" onsubmit="return top.restoreSession()">
        <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>"/>
        <div class="table-responsive">
            <table class="table table-bordered text" cellpadding="4">
                <thead class="thead-light">
                <tr>
                    <th colspan="2" align="center"> Email Settings</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Categorieën</label>
                            </div>
                            <select class="custom-select" name='category_selected' id="category_selected" onchange="onChange()">
                                <option selected>Kiezen...</option>
                                <?php
                                foreach ($pt_cat as $cat) {
                                    echo " <option value='" . attr($cat['pc_catid']) . "' . extra='" . attr($cat['email_filename']) . "'";
                                    echo ">" . xlt($cat['pc_catname']) . "</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                    <td class="detail" nowrap="">
                        <input type="file" name="form_file" size="40"/>
                        <input type="hidden" name="MAX_FILE_SIZE" value="350000000"/>
                    </td>
                <tr>
                    <td class='detail' nowrap>
                        <div id="file_date">Bestandsnaam -
                        </div>
                    </td>
                    <td class='detail' id="filename" nowrap>
                        <?php
                        echo xlt('File not found');
                        ?>
                    </td>
                </tr>
                <tr class="table-light">
                    <td class="detail" colspan="2" align="center">
                        <input class='btn btn-primary' type='submit' name='bn_upload' value='<?php echo xla('Upload / Save') ?>'/>

                    </td>
                </tr>

        </div>

    </form>
</div>
</body>
</html>
