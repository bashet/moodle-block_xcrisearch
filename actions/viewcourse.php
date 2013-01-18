<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 1/10/13
 * Time: 6:15 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../../../config.php');

global $CFG, $USER, $PAGE, $DB;

//include the search mform
require_once($CFG->dirroot."/blocks/xcrisearch/classes/search_mform.php");

require_once($CFG->dirroot."/blocks/xcrisearch/classes/search.class.php");

require_once($CFG->dirroot.'/lib/tablelib.php');

//get the course id param
$course_id      =   optional_param('course_id',SITEID,PARAM_INT);

$pagenum        =   optional_param('pagenum',0,PARAM_INT);

$searchterm     =   optional_param('searchterm','',PARAM_ALPHANUM);

$courseidentifier       =   required_param('id',PARAM_ALPHANUM);

$coursetitle            =   required_param('title',PARAM_ALPHANUM);
$coursetitle            =   urldecode($coursetitle);

$course         =   $DB->get_record('course',array('id'=>$course_id));
$site           =   $DB->get_record('course',array('id'=>SITEID));

//course shortname
$PAGE->navbar->add($course->shortname, $CFG->wwwroot."/course/view.php?id=".$course_id, 'title');

//course shortname
$PAGE->navbar->add(get_string('coursesearch','block_xcrisearch'), $CFG->wwwroot."/blocks/xcrisearch/actions/searchpage.php?course_id=".$course_id."&pagenum=".$pagenum."&searchterm=".$searchterm, 'title');

//set the add
$PAGE->navbar->add(get_string('coursdetails','block_xcrisearch'),null,'title');

$PAGE->set_title($site->fullname." : ".get_string('pluginname','block_lpr'));
$PAGE->set_heading($site->fullname);
$PAGE->set_pagetype('xcrisearch');
$PAGE->set_url($CFG->wwwroot."/blocks/xcrisearch/actions/searchpage.php",array('course_id'=>$course_id));


$searchform     =   new ulcc_xcrisearch;

$courses        =   $searchform->searchCourseId($courseidentifier);

if (!empty($courses))    {

    foreach($courses as $c)   {
        if (strcmp($c->title,$coursetitle))   {
            $viewcourse    =   $c;
        }
    }
}


require_once($CFG->dirroot.'/blocks/xcrisearch/views/viewcourse.html');