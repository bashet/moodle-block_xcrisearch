<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 12/17/12
 * Time: 3:41 PM
 * To change this template use File | Settings | File Templates.
 */


require_once('../../../config.php');


require_once('../classes/search.class.php');


$searchclass    =   new ulcc_xcrisearch();


//$courses    =   $searchclass->searchCourseId('HELZ1Z1314');

$courses    =   $searchclass->searchTerm('FD Commercial Photography');


echo "<pre>";
var_dump($courses);
echo "</pre>";