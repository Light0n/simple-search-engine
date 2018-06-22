<?php

class Organization {
    //Attributes
    public $_name, $_employees = array(), $_searchSuccess = false;

    function addEmployee(Employee $employee){
        $this->_employees[] = $employee;
    }

    //Comparator function by rankScore
    function cmpByRankScore($x, $y){
        return $x->_rankScore < $y->_rankScore; //descending sort
    }

    function searchByTerm($searchTerms){
        //Run seftRank() for each employee based on $searchTerms
        foreach($this->_employees as $employee){
            $employee->selfRank($searchTerms);
        }
        //Sort employees
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