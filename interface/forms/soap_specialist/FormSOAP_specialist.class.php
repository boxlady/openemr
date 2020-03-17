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


define("EVENT_VEHICLE", 1);
define("EVENT_WORK_RELATED", 2);
define("EVENT_SLIP_FALL", 3);
define("EVENT_OTHER", 4);


/**
 * class FormHpTjePrimary
 *
 */
class FormSOAP_specialist extends ORDataObject
{

    /**
     *
     * @access public
     */


    /**
     *
     * static
     */
    var $id;
    var $date;
    var $history_date;
    var $pid;
    var $user;
    var $groupname;
    var $authorized;
    var $activity;
    var $subjective;
    var $history_subjective;
    var $objective;
    var $history_objective;
    var $assessment;
    var $history_assessment;
    var $plan;
    var $history_plan;

    /**
     * Constructor sets all Form attributes to their default value
     */

    function __construct($id = "", $_prefix = "")
    {
        if (is_numeric($id)) {
            $this->id = $id;
        } else {
            $id = "";
            $this->date = date("Y-m-d H:i:s");
        }

        $this->_table = "form_soap_specialist";
        $this->activity = 1;
        $this->pid = $GLOBALS['pid'];
        if ($id != "") {
            $this->populate();
            //$this->date = $this->get_date();
        }
        $this->history();
    }
    function populate()
    {
        parent::populate();
        //$this->temp_methods = parent::_load_enum("temp_locations",false);
    }

    function history()
    {
        parent::get_history();
    }

    function toString($html = false)
    {
        $string .= "\n"
            ."ID: " . $this->id . "\n";

        if ($html) {
            return nl2br($string);
        } else {
            return $string;
        }
    }

    function get_id()
    {
        return $this->id;
    }

    function set_id($id)
    {
        if (!empty($id) && is_numeric($id)) {
            $this->id = $id;
        }
    }

    function get_pid()
    {
        return $this->pid;
    }
    function set_pid($pid)
    {
        if (!empty($pid) && is_numeric($pid)) {
            $this->pid = $pid;
        }
    }

    function get_activity()
    {
        return $this->activity;
    }
    function set_activity($tf)
    {
        if (!empty($tf) && is_numeric($tf)) {
            $this->activity = $tf;
        }
    }

    function get_date()
    {
        return $this->date;
    }
    function set_date($dt)
    {
        if (!empty($dt)) {
            $this->date = $dt;
        }
    }
    function get_user()
    {
        return $this->user;
    }
    function set_user($u)
    {
        if (!empty($u)) {
            $this->user = $u;
        }
    }
    function get_subjective()
    {
        return $this->subjective;
    }
    function set_subjective($data)
    {
        if (!empty($data)) {
            $this->subjective = $data;
        }
    }

    function get_history_subjective()
    {
        return $this->history_subjective;
    }

    function set_history_subjective($data)
    {
        if (!empty($data)) {
            $this->history_subjective .= $data;
        }
    }

    function get_objective()
    {
        return $this->objective;
    }
    function set_objective($data)
    {
        if (!empty($data)) {
            $this->objective = $data;
        }
    }

    function get_history_objective()
    {
        return $this->history_objective;
    }

    function set_history_objective($data)
    {
        if (!empty($data)) {
            $this->history_objective .= $data;
        }
    }

    function get_assessment()
    {
        return $this->assessment;
    }
    function set_assessment($data)
    {
        if (!empty($data)) {
            $this->assessment = $data;
        }
    }

    function get_history_assessment()
    {
        return $this->history_assessment;
    }

    function set_history_assessment($data)
    {
        if (!empty($data)) {
            $this->history_assessment .= $data;
        }
    }

    function get_plan()
    {
        return $this->plan;
    }
    function set_plan($data)
    {
        if (!empty($data)) {
            $this->plan = $data;
        }
    }

    function get_history_plan()
    {
        return $this->history_plan;
    }

    function set_history_plan($data)
    {
        if (!empty($data)) {
            $this->history_plan .= $data;
        }
    }

    function persist()
    {
        parent::persist();
    }
}   // end of Form
