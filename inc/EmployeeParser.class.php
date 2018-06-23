<?php

class EmployeeParse{
    //The organization 
    private $_organization;

    function parseOrganization($fileContents){
        //Instantiate a new organization
        $this->_organization = new Organization();
        //create an array out of each line of the file (split by newline character)
        $lines = explode("\n", $fileContents);

        //Iterating through each line of the file except 1st line
        for($i=1; $i < count($lines); $i++){
            //Split the columns up into an array
            $cols = explode(",",$lines[$i]);
            try{
                //Check we have the right amount of columns
                if(count($cols) != NUM_COLS){
                    throw new Exception("EmployeeParse: There is a problem on line: ".$i."\n<br>",100);
                }
            } catch (Exception $e){
                echo $e->getMessage();
                continue;
            }
            //Trim out the white space
            for($j=0; $j < count($cols); $j++){
                $cols[$j] = trim($cols[$j]);
            }
            //Create and add email property to cols array
            $cols[] = strtolower($cols[FIRST_NAME]).".".strtolower($cols[LAST_NAME])."@csis3280.net";

            //Instantiate a new employee
            $employee = new Employee($cols);
            //Add employee to organization
            $this->_organization->addEmployee($employee);
        }
        return $this->_organization;//return a organization
    }
}

?>