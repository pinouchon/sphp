<?php

class Topic extends MyModel {

    public function __construct() {
        $this->validators['title'] = array("Validator::string", array('min' => 10, 'max' => 255));
        $this->validators['body'] = array("Validator::string", array('min' => 30, 'max' => 1000000));
//        $this->validators['email'] = array("Validator::email", array('existant' => 1));
//        $this->validators['dob'] = array("Validator::birthday", array('min' => 18));
//        $this->validators['description'] = array("Validator::string", array('min' => 0, 'max' => 1500, 'presence' => false));
        $this->formFields = array_keys($this->validators);
    }

}