<?php
/**
 * soap form specialist
 * Forms generated from formsWiz
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Julie Buurman
 * @copyright Copyright (c) 2020 Julie Buurman <Julie.Buurmanr@gmail.com>
 */


require_once("../../globals.php");
require_once("$srcdir/api.inc");

require("C_FormSOAP_specialist.class.php");

$c = new C_FormSOAP_specialist();
echo $c->view_action($_GET['id']);
