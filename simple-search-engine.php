<?php
 /**
 * 
 * Description: Simple Search Engine
 * 
**/

//Include constant definitions 
require_once("./inc/config.inc.php");
//Incldue the utility classes
require_once("./inc/FileAgent.class.php");
require_once("./inc/EmployeeParser.class.php");
require_once("./inc/Page.class.php");
//Include the interfaces
require_once("./inc/ISelfRank.interface.php");
//Include the classes
require_once("./inc/Person.class.php");
require_once("./inc/Employee.class.php");
require_once("./inc/Organization.class.php");

//Set page title
Page::$title = "CSIS 3280 - Assigment #1 - Group 2";

//Display the Page headder
Page::header();

//Show the search form
Page::searchForm();

//Get contents file
$contents = FileAgent::getFileContents(FILE_PATH);

//Instantiate EmployeeParse
$employeeParse = new EmployeeParse();

//Parse the employees from file contents and create a organization object
$organization = $employeeParse->parseOrganization($contents);
$organization->_name = "CSIS3280 Inc."; // set organization name

//If the request method was get and "searchTerms" issset
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['searchTerms'])) {
    if(strlen($_GET["searchTerms"]) > 3){//"searchTerms" is not fewer than 3
        //Sorting employees by search terms
        $organization->searchByTerm($_GET['searchTerms']);
        if($organization->_searchSuccess){//if have results
            //display search results
            Page::showEmployee($organization, $_GET['searchTerms']);
        }
        else{
            //display no results message
            Page::noResult($_GET['searchTerms']);
        }
    }else{
        //display invalid search terms message;
        Page::invalidSeachTerms($_GET['searchTerms']);
    }
}

//Display the page footer
Page::footer();
?>
