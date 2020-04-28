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


require_once(dirname(__FILE__).'/../../globals.php');
require_once($GLOBALS["srcdir"]."/api.inc");

function verpleegkundige_soap_report($pid, $encounter, $cols, $id)
{
    $cols = 1; // force always 1 column
    $count = 0;
    $data = formFetch("form_soap_verpleegkundige", $id);
    if ($data) {
        print "<table><tr>";
        foreach ($data as $key => $value) {
            if ($key == "id" || $key == "pid" || $key == "user" || $key == "groupname" || $key == "authorized" || $key == "activity" || $key == "date" || $value == "" || $value == "0000-00-00 00:00:00") {
                continue;
            }

            if ($value == "on") {
                $value = "yes";
            }

            $key=ucwords(str_replace("_", " ", $key));
                                                                              //Updated by Sherwin 10/24/2016
            print "<td><span class=bold>" . xlt($key) . ": </span><span class=text>" . nl2br(text($value)) . "</span></td>";
            $count++;
            if ($count == $cols) {
                $count = 0;
                print "</tr><tr>\n";
            }
        }
    }

    print "</tr></table>";
}