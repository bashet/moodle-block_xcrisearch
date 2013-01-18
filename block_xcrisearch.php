<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 1/11/13
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */


class block_xcrisearch extends block_list  {

    function init() {
        $this->title = get_string('pluginname', 'block_xcrisearch');
    }


    function get_content()  {
        global  $CFG,$COURSE,$USER,$DB;

        if ($this->content !== null) {
            return $this->content;
        }




        //first lets get the moodle record for the current course
        $course =   $DB->get_record('course',array('id'=>$COURSE->id));

        $noxcri   =   true;

        //if the current course is not empty
        if (!empty($course))   {
            //if the current course has an idnumbert
            if (!empty($course->idnumber))   {

                require_once($CFG->dirroot."/blocks/xcrisearch/classes/search.class.php");

                $xcrisearch     =   new ulcc_xcrisearch;
                //get the current courses xcri details as we will use this to search for
                $currentcourses    =   $xcrisearch->searchCourseId($course->idnumber,false,1);

                if (!empty($currentcourses))    {

                    $xcourse   = array_pop($currentcourses);
                    //now the question mark? what do I use to search for the course of the higher level?
                    //for now I will use the subject hope that suffices

                    $xcricourses    =   $xcrisearch->searchTerm($xcourse->subject,$xcourse->creditlevel,0);

                    if (!empty($xcricourses)) {
                        $noxcri     =   false;
                        $i  =   0;

                        $this->content->items[] = get_string('followingcourses','block_xcrisearch');
                        $this->content->icons[] = "";

                        foreach ($xcricourses as $xc)  {
                            $label  =   $xc->title;
                            $urllabel   =   urlencode($xc->title);
                            $url    =   "{$CFG->wwwroot}/blocks/xcrisearch/actions/viewcourse.php?course_id={$COURSE->id}&page=0&searchterm={$xcourse->subject}&id={$xc->identifier}&title={$urllabel}";
                            $this->content->items[] = html_writer::tag('a',$label, array('href' => $url));
                            $this->content->icons[] = "";
                            $i++;
                            if ($i > 3) break;
                        }

                        //if there are more than 3 courses found then we will show a link to view the other results
                        if (count($xcricourses) > 3)   {
                            $url    =   "{$CFG->wwwroot}/blocks/xcrisearch/actions/searchpage.php?course_id={$COURSE->id}&page=0&searchterm={$xcourse->subject}";
                            $this->content->items[] = html_writer::tag('a',get_string('viewrecommendations','block_xcrisearch'), array('href' => $url));
                            $this->content->icons[] = "";
                        }


                    }



                }

            }
        }

        $this->content->items[] = get_string('searchdescription','block_xcrisearch');
        $this->content->icons[] = "";

        $label  =   get_string('searchterm','block_xcrisearch');
        $url    =   "{$CFG->wwwroot}/blocks/xcrisearch/actions/searchpage.php?course_id={$COURSE->id}";
        $this->content->items[] = html_writer::tag('a',get_string('searchforcourses','block_xcrisearch'), array('href' => "/blocks/xcrisearch/actions/searchpage.php?course_id={$COURSE->id}"));
        $this->content->icons[] = "";


        if (!empty($noxcri))    {

            $this->content->items[] = get_string('norecommendedcourses','block_xcrisearch');
            $this->content->icons[] = "";

            $this->content->items[] = html_writer::tag('a',get_string('teamname','block_xcrisearch'), array('href' => '#'));
            $this->content->icons[] = "";
        }

        return $this->content;
    }

    /**
     * Prevent the user from having more than one instance of the block on each
     * course.
     *
     * @return bool false
     */
    function instance_allow_multiple() {
        return false;
    }

    /**
     * Allow the user to set specific configuration options for the instance of
     * the block attached to a course.
     *
     * @return bool true
     */
    function instance_allow_config() {

        return false;
    }

    /**
     * Allow the user to set sitewide configuration options for the block.
     *
     * @return bool true
     */
    function has_config() {
        return true;
    }
}