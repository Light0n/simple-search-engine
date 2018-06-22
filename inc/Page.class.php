<?php

//This Class is to construct our html page.

class Page  {

  static public $title;

  //Constructor - Set the title when it is passed in __construct($newTitle)  {
  // function __construct($newTitle = "Please name your page"){
  //   $this->title = $newTitle;
  // }

  //This function displays the html header
  static function header() {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>'.self::$title.'</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <style>
            body{
                width: 1100px;
                margin: 5px auto;
            }
            .table td {
                vertical-align: middle;
            }
            .table th a{
                text-decoration: none;
            }
            .table-scroll-vertical {
                display: block;
                max-height: 70vh;
                overflow-y: auto;
                margin-bottom:5px;
            }
        </style>
    </head>
    <body>
        <h1 class="display-4 text-secondary">'.self::$title.'</h1>';
  }

  //This function displays the html footer
  static function footer() {
    echo '</body>
    </html>';
  }

  //This function displays the upload form for the CSV file
  static function uploadForm() { 
    echo '<form>
    <div class="form-row align-items-center" method="GET">
        <div class="col-sm-4 my-1">
        <input type="text" class="form-control" id="searchTerms" name="searchTerms" placeholder="Search Terms..." required>
        </div>
        <div class="col-auto my-1">
        <button type="submit" class="btn btn-primary">Go!</button>
        </div>
    </div>
    </form>';
  }

  static function showEmployee($organization, $searchTerms)  {
    //Setup the table roster 
    echo '<!-- Table begin -->
    <div class="table-scroll-vertical">
    <table class="table table-sm table-striped" id="employees">
        <thead class="">
          <tr>
            <td colspan="6"><h1>'.$organization->_name.'</h1></td>
          </tr>
          <tr>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Job Title</th>
            <th scope="col">Department</th>
          </tr>
        </thead>
        <tbody>';
    $total = 0;
    //Iterate through the roster and print it out
    foreach ($organization->_employees as $employee)  {
        if($employee->_rankScore){//_rankScore > 0
            $total++;
            echo '<tr>
              <td>'.$employee->_firstName.'</td>
              <td>'.$employee->_lastName.'</td>
              <td>'.$employee->_email.'</td>
              <td>'.$employee->_phone.'</td>
              <td>'.$employee->_jobTitle.'</td>
              <td>'.$employee->_department.'</td>
            </tr>';
        }
    }

    //End the table roster
    echo '</tbody>
    </table>
    </div>
    <!-- Table end -->';
    //Display the number of search results 
    echo '<div class="alert alert-success" role="alert">Search for "<strong>'.$searchTerms.'</strong>", '.$total.' results found.</div>';
  }

  static function noResult($searchTerms){
    echo '<div class="alert alert-danger" role="alert">Search for <strong>'.$searchTerms.'</strong>. No results founds! Please enter another search term.</div>';
  }

  static function invalidSeachTerms($searchTerms){
    echo '<div class="alert alert-warning" role="alert">Search for <strong>'.$searchTerms.'</strong>. Invalid search terms! Please enter not fewer than 3 characters.</div>';
  }
}//End Page Class
?>
