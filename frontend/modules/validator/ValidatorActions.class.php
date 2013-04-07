<?php

class ValidatorActions {

  public function validate() {
    if (!isset($_GET['classname']) ||
            !isset($_GET['fieldname']) ||
            !isset($_GET['value'])) {
      throw new RuntimeException('You must pass get params too validate 
        action: classname, fieldname, value');
    }
    $fieldName = $_GET['fieldname'];
    
    $model = new $_GET['classname'];
    $validator = $model->validators[$fieldName];
    $validatorFunc = $validator[0];
    $validatorParams = isset($validator[1]) ? $validator[1] : null;
    
    var_dump(call_user_func_array($validatorFunc, array($fieldName, $_GET['value'], $validatorParams)));
  }

}