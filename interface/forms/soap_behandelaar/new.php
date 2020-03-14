<?php
/**
 * soap form behandelaar
 * Forms generated from formsWiz
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Julie Buurman
 * @copyright Copyright (c) 2020 Julie Buurman <Julie.Buurmanr@gmail.com>
 */


require_once("../../globals.php");
require_once("$srcdir/api.inc");

require("C_FormSOAP_behandelaar.class.php");

$c = new C_FormSOAP_behandelaar();
echo $c->default_action();
