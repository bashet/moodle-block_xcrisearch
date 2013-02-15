<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 12/17/12
 * Time: 1:48 PM
 * To change this template use File | Settings | File Templates.
 */
class ulcc_xcrisearch    {

    private     $xmlfile;


    function __construct()   {

            global  $CFG;
            $this->xmlfile    =   $CFG->dirroot."/blocks/xcrisearch/pca_xml.xml";
    }


    /**
     *  @param $courseid    the id of the course this will be used when searching
     *  @param int $resreturn the number of results to return
     *
     *
     */
    function searchCourseId($courseid,$level=false,$resreturn=3)  {
        return $this->search($courseid,'courseid',$resreturn,$level);
    }

    function searchTerm($searchterm,$level=false,$resreturn=0) { // was $resreturn=3
        return $this->search($searchterm,'term',$resreturn,$level);
    }


    /**
     * Searches the xml file to see if it contains the term given
     *
     * @param $searchterm the term to be searched for
     * @param $type the type of search needed
     * @param $resreturn the number of results to return
     * @param int $level the level that the return courses should be higher than
     * @return array
     */
    function search($searchterm,$type,$resreturn,$level=0)   {

        $ns = array(
            'content' => 'http://purl.org/rss/1.0/modules/content/',
            'mlo' => 'http://purl.org/net/mlo',
            'dc' => 'http://purl.org/dc/elements/1.1/'
        );


        $doc = new DOMDocument();
        $fileloaded =   $doc->load($this->xmlfile);
        $courses   =   $doc->getElementsByTagName('course');
        
        /*foreach ($courses as $course){
            print_r($course);
            echo '<br>';
        }
        */
        $coursesfound   =   array();

        if (!empty($courses))   {
            foreach($courses as $c)   {
                if ($type   ==  'courseid')   {
                    //get all identifier tags from the element
                    $identifier     =   $c->getElementsByTagNameNS('http://purl.org/dc/elements/1.1/', 'identifier');
                    foreach($identifier as $i)  {
                        //we need to check if any of the courses have the identifier
                        if ($i->textContent == $searchterm)   {
                            //normalise the xml course element into a object
                            if (empty($level)) {
                                $coursesfound[] = $this->normaliseCourse($c);
                            } else {
                                $tempcourse =   $this->normaliseCourse($c); 
                                if ($tempcourse->creditlevel > $level)  {
                                    $coursesfound[] =   $tempcourse;
                                }
                            }
                        }
                    }
                } else {
                    //check if any of the the text in the course matches the search term
                    if (strpos($c->textContent,$searchterm) !== false )  {

                        //maybe use this instead of below
                        //if (($level != 0 && $c->level > $level) || $level == 0)  {

                        if (empty($level)) {
                            $coursesfound[] = $this->normaliseCourse($c);
                        } else {
                            $tempcourse =   $this->normaliseCourse($c);
                            if ($tempcourse->creditlevel > $level)  {
                                $coursesfound[] =   $tempcourse;
                            }
                        }
                    }
                }

                if (count($coursesfound) == $resreturn && !empty($resreturn)) break;
            }
        }

        return $coursesfound;
    }


    /**
     * In order to be able to make use of a course that has been selected the data in it must be normalised
     *
     * @param $course a DOMDocument object representing a course
     *
     * */
    function normaliseCourse($course)  {
        $ns = array
        (
            'content' => 'http://purl.org/rss/1.0/modules/content/',
            'mlo' => 'http://purl.org/net/mlo',
            'dc' => 'http://purl.org/dc/elements/1.1/'
        );

        $courseobject   =   new stdClass();


        //get course description (dc:description)
        $courseobject->description        =     $this->getElementDataByTagNameNS($course,$ns['dc'],'description');

        //get course identifier (dc:identifier)
        $courseobject->identifier        =     $this->getElementDataByTagNameNS($course,$ns['dc'],'identifier');

        //get course subject (dc:subject)
        $courseobject->subject        =     $this->getElementDataByTagNameNS($course,$ns['dc'],'subject');

        //get course title (dc:title)
        $courseobject->title        =     $this->getElementDataByTagNameNS($course,$ns['dc'],'title');

        //get course url (mlo:url)
        $courseobject->url        =     $this->getElementDataByTagNameNS($course,$ns['mlo'],'url');

        //get course abstract (abstract)
        $courseobject->abstract        =     $this->getElementDataByTagNameNS($course,'','abstract');

        //get course assessment (mlo:assessment)
        $courseobject->assessment        =     $this->getElementDataByTagNameNS($course,$ns['mlo'],'assessment');

        //get learning outcome (learning outcome)
        $courseobject->learningoutcome        =     $this->getElementDataByTagNameNS($course,'','learningOutcome');

        //get prerequisite (mlo:prerequisite)
        $courseobject->prerequisite        =     $this->getElementDataByTagNameNS($course,$ns['mlo'],'prerequisite');

        //create  qualification object add following to that and then add qualification object to course object

        //get qualification title (dc:title)
        $courseobject->qualtitle        =     $this->getElementDataByTagNameNS($course,$ns['dc'],'title');

        //get awarded by  (awardedby)
        $courseobject->qualawardedby        =     $this->getElementDataByTagNameNS($course,'','awardedby');

        //create  credit object add following to that and then add credit object to course object

        //get course credit scheme (credit:scheme)
        $courseobject->creditscheme        =     $this->getElementDataByTagNameNS($course,'','scheme');

        //get course credit level (credit:level)
        $courseobject->creditlevel        =     $this->getElementDataByTagNameNS($course,'','level');

        //get course credit value (credit:value)
        $courseobject->creditvalue        =     $this->getElementDataByTagNameNS($course,'','value');

        //create  presentation  object add following to that and then add presentation object to course object

        //get start date (mlo:start)
        $courseobject->presentationstart        =     $this->getElementDataByTagNameNS($course,$ns['mlo'],'start');

        //get apply to (applyTo)
        $courseobject->presentationapplyto        =     $this->getElementDataByTagNameNS($course,'','applyTo');

        //get study Mode (studyMode)
        $courseobject->presentationstudymode        =     $this->getElementDataByTagNameNS($course,'','studyMode');

        //get attendance Mode (attendanceMode )
        $courseobject->presentationattendancemode        =     $this->getElementDataByTagNameNS($course,'','attendanceMode');

        //get attendance Pattern (attendancePattern)
        $courseobject->presentationattendancepattern        =     $this->getElementDataByTagNameNS($course,'','attendancePattern');

        return $courseobject;
    }


    /**
     * @param @param $course a DOMDocument object representing a course
     * @param $namespace namespace of the data to be used
     * @param $tag tag that the data will be retrieved from
     * @param int $number the number of the element e.g the first element or second etc
     *
     * @return string
     */
    function getElementDataByTagNameNS($element,$namespace,$tag,$number=1)   {
        $returndata     =   "";

        $ele           =   (!empty($namespace)) ? $element->getElementsByTagNameNS($namespace, $tag) : $element->getElementsByTagName($tag);
        if (!empty($ele))   {
            foreach($ele as $data)   {
                $returndata  =   $data->textContent;
                break;
            }
        }
        return $returndata;
    }

}

