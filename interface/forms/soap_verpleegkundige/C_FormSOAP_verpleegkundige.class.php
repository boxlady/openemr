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


require_once($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormSOAP_verpleegkundige.class.php");

use OpenEMR\Common\Csrf\CsrfUtils;

class C_FormSOAP_verpleegkundige extends Controller
{

    var $template_dir;

    function __construct($template_mod = "general")
    {
        parent::__construct();
        $this->template_mod = $template_mod;
        $this->template_dir = dirname(__FILE__) . "/templates/";
        $this->assign("FORM_ACTION", $GLOBALS['web_root']);
        $this->assign("DONT_SAVE_LINK", $GLOBALS['form_exit_url']);
        $this->assign("STYLE", $GLOBALS['style']);
        $this->assign("CSRF_TOKEN_FORM", CsrfUtils::collectCsrfToken());
    }

    function default_action()
    {
        $form = new FormSOAP_verpleegkundige();
        $this->assign("data", $form);
        return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
    }

    function view_action($form_id)
    {
        if (is_numeric($form_id)) {
            $form = new FormSOAP_verpleegkundige($form_id);
        } else {
            $form = new FormSOAP_verpleegkundige();
        }

        $dbconn = $GLOBALS['adodb']['db'];

        $this->assign("data", $form);

        return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
    }

    function default_action_process()
    {
        if ($_POST['process'] != "true") {
            return;
        }

        $this->form = new FormSOAP_verpleegkundige($_POST['id']);
        parent::populate_object($this->form);

        $this->form->persist();
        if ($GLOBALS['encounter'] == "") {
            $GLOBALS['encounter'] = date("Ymd");
        }

        if (empty($_POST['id'])) {
            addForm($GLOBALS['encounter'], "SOAP_verpleegkundige", $this->form->id, "soap_verpleegkundige", $GLOBALS['pid'], $_SESSION['userauthorized']);
            $_POST['process'] = "";
        }

        return;
    }
}
