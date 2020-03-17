<?php
/**
 * soap form verpleegkundige
 * Forms generated from formsWiz
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Julie Buurman
 * @copyright Copyright (c) 2020 Julie Buurman <Julie.Buurmanr@gmail.com>
 */


require_once("../../globals.php");
require_once("$srcdir/api.inc");

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

require("C_FormSOAP_verpleegkundige.class.php");
$c = new C_FormSOAP_verpleegkundige();
echo $c->default_action_process($_POST);
@formJump();
