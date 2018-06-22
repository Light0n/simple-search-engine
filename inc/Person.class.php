<?php

class Person {
    //Attributes
    public $_firstName = "", $_lastName = "";
    //Constructor
    function __construct($fistName, $lastName){
        $this->_firstName = $fistName;
        $this->_lastName = $lastName;
    }
}