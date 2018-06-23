<?php

class Organization {
    //Attributes
    public $_name, $_employees = array();
    public  $_searchSuccess = false;//true if have search results

    //Add employee to employee array
    function addEmployee(Employee $employee){
        $this->_employees[] = $employee;
    }

    //Comparator function by rankScore
    function cmpByRankScore($x, $y){
        return $x->_rankScore < $y->_rankScore; //descending order
    }

    function searchByTerm($searchTerms){
        //Run seftRank() for each employee based on $searchTerms
        foreach($this->_employees as $employee){
            $employee->selfRank($searchTerms);
        }
        //Sort employees by $_rankScore
        usort($this->_employees, array('Organization','cmpByRankScore'));
        //Update _searchSuccess flag
        foreach($this->_employees as $employee){
            //Check the first employee _rankScore and change _searchSuccess flag
            $employee->_rankScore > 0 ? $this->_searchSuccess = true : $this->_searchSuccess = false;
            break; // terminate loop
        }
    }
}

?>