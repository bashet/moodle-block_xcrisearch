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


$course         =   $DB->get_record('course',array('id'=>$course_id));
$site           =   $DB->get_record('course',array('id'=>SITEID));

$resultsperpage =   50;
$search_carried_out      =  false;
$pages          =   0;

//course shortname
$PAGE->navbar->add($course->shortname, $CFG->wwwroot."/course/view.php?id=".$course_id, 'title');

//set the add
$PAGE->navbar->add(get_string('coursesearch','block_xcrisearch'),null,'title');



$PAGE->set_title($site->fullname." : ".get_string('pluginname','block_xcrisearch'));
$PAGE->set_heading($site->fullname);
$PAGE->set_pagetype('xcrisearch');
$PAGE->set_url($CFG->wwwroot."/blocks/xcrisearch/actions/searchpage.php",array('course_id'=>$course_id));



$courses                =   false;


$currentcourseslevel    =   0;


//retrieve the level of the current course
 if (!empty($course))   {
     //if the current course has an idnumbert
     if (!empty($course->idnumber))   {
         $xcrisearch     =   new ulcc_xcrisearch;
         //get the current courses xcri details as we will use this to search for
         $currentcourses    =   $xcrisearch->searchCourseId($course->idnumber,false,1);

         if (!empty($currentcourses))    {
             $xcourse   = array_pop($currentcourses);
             $currentcourseslevel   =   (!empty($xcourse->creditlevel)) ?   $xcourse->creditlevel : $currentcourseslevel;
             
         }
     }
 }


$mform  =   new search_mform($course_id,$currentcourseslevel);



if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/course/view.php?id='.$course_id);
}

//was the form submitted?
// has the form been submitted?
if($mform->is_submitted()) {
    // check the validation rules
    if($mform->is_validated()) {

        //get the form data submitted
        $formdata = $mform->get_data();
        // process the data
        $courses     =  $mform->process_data($formdata);
        $search_carried_out      =  true;
    }
}

if (!empty($searchterm))   {

    $searchform     =   new ulcc_xcrisearch;
    $searchform->searchterm     =   $searchterm;
    $searchform->course_id      =   $course_id;

    $mform->set_data($searchform);

    if (empty($courses)) {
        $courses    =   $searchform->searchTerm($searchterm,$currentcourseslevel,$resultsperpage);
        //$courses    =   $searchform->searchTerm($searchterm,$resultsperpage);
    }
}

if (!empty($courses))   {

    $numresults =   count($courses);

    //find out how many pages
    $pages  =   ceil($numresults / $resultsperpage);

    $startrecord    =   $pagenum * $resultsperpage;
    $endrecord      =   $startrecord    +  $resultsperpage;
/*
    $flextable  =   new flexible_table('coursesearch');

    $flextable->define_baseurl($CFG->wwwroot."/blocks/xcrisearch/actions/searchpage.php?courseid={$course_id}");

    $columns    =   array();
    $columns[]    =   'coursetitle';
    $columns[]    =   'subject';
    $columns[]    =   'identifier';
    $columns[]    =   'view';

    $headers    =   array();
    $headers[]  =   get_string('title','block_xcrisearch');
    $headers[]  =   get_string('subject','block_xcrisearch');;
    $headers[]  =   get_string('identifier','block_xcrisearch');;
    $headers[]  =   '';

    $flextable->define_columns($columns);
    $flextable->define_headers($headers);


    $flextable->setup();

    for($i=$startrecord;$i < $endrecord; $i++)   {
        if (!empty($courses[$i]))   {

            $data['coursetitle']              =      $courses[$i]->title;
            $data['subject']            =      $courses[$i]->subject;
            $data['identifier']         =      $courses[$i]->identifier;
            $data['view']               =      "";
            $flextable->add_data_keyed($data);
        } else {
            break;
        }
    }
*/
}


require_once($CFG->dirroot.'/blocks/xcrisearch/views/searchpage.html');