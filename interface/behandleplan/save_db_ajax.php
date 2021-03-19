<?php
require_once("../globals.php");

use OpenEMR\Common\Csrf\CsrfUtils;



$type = $_GET['type'];
$action = $_GET['action'];
$name = $_GET['sname'] ?? '';
$notes = $_GET['notes'] ?? '';
$extranalinfo = $_GET['external_info'] ?? '';
$Action = $_GET['save_to_db'];
$id = $_GET['id'];
$ing_id =$_GET['$ing_id'];
$more_info   =$_GET['more_info'];

if ($type == 'plan') {
    if ($action == 'save') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        $dbid = sqlInsert("Insert into `behandleplanen` (`name`, info, pdf) values(?,?,?)", array($name, $notes, $more_info));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $dbid, 'name' => $name));
    }
    if ($action == 'update') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        sqlQuery("Update `behandleplanen` set `name` =?, info =?, pdf = ? where id =? ", array($name, $notes, $more_info));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $id, 'name' => name));
    }
    if ($action == 'delete') {
        sqlStatement("Delete from behandleplanen where id =?", $_POST['id']);
        //todo remove from selector

    }
}

if ($type == 'ing') {
    if ($action == 'save') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        $dbid = sqlInsert("Insert into `bhp_ingredienten` (`name`, `notes`, `extranal_info`) values(?,?,?) ", array($name, $notes, $extranalinfo));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $dbid, 'name' => $name));
    }
    if ($action == 'update') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        sqlQuery("update bhp_ingredienten set name = ?, notes =?,extranal_info=? where id = ?", array($name, $notes, $extranalinfo, $id));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $id, 'name' => name));
    }
    if ($action == 'delete') {
        sqlStatement("Delete from bhp_ingredienten where id =?", $_POST['id']);
        //todo remove from selector

    }
}
if ($type == 'path') {
    if ($action == 'save') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        $dbid = sqlInsert("Insert into `bhp_pathway` (`name`, `up/down`) values(?,?) ", array($name, $notes));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $dbid, 'name' => $name));
    }
    if ($action == 'update') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        sqlQuery("Update `bhp_pathway` set `name` =?, `up/down` =? where id =? ",   array($name, $notes));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $id, 'name' => name));
    }
    if ($action == 'delete') {
        sqlStatement("Delete from bhp_pathway where id =?", $_POST['id']);
        //todo remove from selector

    }
}
if ($type == 'contra') {
    if ($action == 'save') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        $dbid = sqlInsert("Insert into `bhp_contraindicaties` (`name`, ing_id, reaction) values(?,?,?)", array($name, $ing_id, $notes));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $dbid, 'name' => $name));
    }
    if ($action == 'update') {
        // load patients ids for select2.js library, expect receive 'text' and 'id'.
        sqlQuery("Update `bhp_contraindicaties` set `name` =?, ing_id =?, reaction = ? where id =? ", array($name, $ing_id, $notes));
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(array('dbid' => $id, 'name' => name));
    }
    if ($action == 'delete') {
        sqlStatement("Delete from bhp_contraindicaties where id =?", $_POST['id']);
        //todo remove from selector

    }
}

