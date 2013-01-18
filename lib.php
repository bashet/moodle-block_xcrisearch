<?php

require_once('../../../config.php');

//require the lpr db class
require_once($CFG->dirroot.'/blocks/cgr/db/db.php');

/**
 *
 *
 * @copyright &copy; @{YEAR} University of London Computer Centre
 * @author http://www.ulcc.ac.uk, http://moodle.ulcc.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package
 * @version
 */

/**
 * Changes the status of a CGR
 *
 *
 * @param $cgr_id
 * @param $status
 */
function change_cgr_status($cgr_id,$status)    {
    $db     =   new cgr_db();
    $cgr             =   $db->get_cgr($cgr_id);
    $cgr->complete   = $status;
    $db->update_cgr($cgr);
}

/**
 * Removes a cgr and all records that reference it in the db
 *
 * @param int $cgr_id the id of the cgr that you want to delete
 */
function remove_cgr($cgr_id)   {
    $db     =   new cgr_db();
    $db->delete_cgr($cgr_id);
}


function series_end($cgr_id)   {
    $db     =   new cgr_db();
    $cgr             =   $db->get_cgr($cgr_id);
    $seriescgrs     =   $db->get_series_cgrs($cgr->coursecode,$cgr->series);

    if (!empty($seriescgrs))    {
        foreach ($seriescgrs as $sc)  {
            $sc->seriesend   = 1;
            $db->update_cgr($sc);
        }
    }
}

function get_course_series_number($coursecode) {

    $db     =   new cgr_db();
    $cgrs   =   $db->get_incomplete_series($coursecode);

    if (!empty($cgrs))  {
        foreach($cgrs   as $c)  {
            $seriesnumber   =   $c->series;
        }
        return (!empty($seriesnumber)) ? $seriesnumber : 1;

    } else { $lastseries    =   $db->get_last_series_number($coursecode);
        return (!empty($lastseries)) ? $lastseries->num + 1 : 1;
    }
}