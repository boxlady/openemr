<?php

/**
 * interface/main/holidays/Holidays_Controller.php implementation of holidays logic.
 *
 * This class contains the implementation of all the logic
 * included in the holidays calendar story.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    sharonco <sharonco@matrix.co.il>
 * @copyright Copyright (c) 2016 Sharon Cohen <sharonco@matrix.co.il>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

class Email_Controller
{

    const UPLOAD_DIR = "documents/email_attachments";

    public $target_file;

    function __construct()
    {
    }

    public function get_target_file()
    {
        return $this->set_target_file();
    }

    public function set_target_file($filename)
    {
        $this->target_file = $GLOBALS['OE_SITE_DIR'] . "/" . self::UPLOAD_DIR . "/" . $filename;
    }

    /**
     * This function uploads the csv file
     * @param $files
     * @return bool
     */
    public function upload_file($files)
    {
        if (!file_exists($GLOBALS['OE_SITE_DIR'] . "/" . self::UPLOAD_DIR)) {
            if (!mkdir($GLOBALS['OE_SITE_DIR'] . "/" . self::UPLOAD_DIR . "/", 0700)) {
                return false;
            }
        }
        $target = $GLOBALS['OE_SITE_DIR'] . "/" . self::UPLOAD_DIR . "/" . basename($files['form_file']['name']);
        //If we need to exculde file types
        $file_type = pathinfo($target, PATHINFO_EXTENSION);

        if (move_uploaded_file($files['form_file']['tmp_name'], $target)) {
            return true;
        }

        return false;
    }

    public function get_file_csv_data()
    {
        $file = array();
        if (file_exists($this->target_file)) {
            $file['date'] = date("d/m/Y H:i:s", filemtime($this->target_file));
        }

        return $file;
    }

    public function save_attachment($catid, $file)
    {
        $filepath = $GLOBALS['OE_SITE_DIR'] . "/" . self::UPLOAD_DIR . "/" . basename($file['form_file']['name']);
        $filename = $file['form_file']['name'];
        if (sqlQuery("Update openemr_postcalendar_categories set email_filename=?, email_url=?  where pc_catid =?", array($filename, $filepath, $catid))) {
            return true;
        }
        return false;
    }

    public function clear_attachment($catid)
    {
        if ($catid != null || $catid != '') {
            sqlQuery("Update openemr_postcalendar_categories set email_filename=?, email_url=?  where pc_catid =?", array('', '', $catid));
            return true;
        }
    }
}
