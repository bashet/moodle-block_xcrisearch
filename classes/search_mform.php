<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 1/10/13
 * Time: 6:16 PM
 * To change this template use File | Settings | File Templates.
 */

global $CFG;

require_once($CFG->libdir.'/formslib.php');

class search_mform extends moodleform {
    function __construct($course_id,$currentcourselevel) {
        global  $CFG;

        $this->course_id    =   $course_id;
        $this->currentcourselevel   =   $currentcourselevel;

        parent::__construct("{$CFG->wwwroot}/blocks/xcrisearch/actions/searchpage.php?course_id={$course_id}");
    }


    function definition() {
        global $USER, $CFG;

        $mform =& $this->_form;

        $mform->addElement('html', '<fieldset id="reportfieldset" class="clearfix">');
        $mform->addElement('html', '<legend class="ftoggler">'.get_string('search','block_xcrisearch').'</legend>');

        $mform->addElement('hidden', 'course_id',$this->course_id);
        $mform->setType('course_id', PARAM_INT);

        $mform->addElement('hidden', 'currentcourselevel',$this->currentcourselevel);
        $mform->setType('currentcourselevel', PARAM_INT);

        $mform->addElement('text', 'searchterm', get_string('searchterm', 'block_xcrisearch'));

        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('submit'));
        $buttonarray[] = &$mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);


        $mform->addElement('html', '</fieldset>');
    }

    /**
     * TODO comment this
     */
    function process_data($data) {
        require_once('../classes/search.class.php');
        $searchclass    =   new ulcc_xcrisearch();
        $courses        =   $searchclass->searchTerm($data->searchterm,$data->currentcourselevel);
        return          $courses;
    }


    /**
     * TODO comment this
     */
    function definition_after_data() {

    }
}