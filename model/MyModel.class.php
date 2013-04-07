<?php

class MyModel extends Model {

  public $validators = array();
  public $formFields = array();

  /**
   *
   * @return MyModel
   */
  public static function factory() {
    return parent::factory(get_called_class());
  }

  public function populate($data) {
    if (empty($this->formFields)) {
      throw new RuntimeException("Model " . get_class($this) . " does not have any formFields");
    }
    foreach ($this->formFields as $fieldName) {
      $this->$fieldName = $data[$fieldName];
    }
    return $this;
  }

  public function countErrors($errors) {
    $nb = 0;
    foreach ($errors as $content) {
      if ($content != false)
        $nb++;
    }
    return ($nb);
  }

  public function validate() {
    $errors = array();
    if (!is_array($this->validators)) {
      throw new RuntimeException("Model X have no validators.");
    }

    foreach ($this->validators as $fieldName => $validatorArr) {
      if (($error = $this->validateField($fieldName)) != false) {
        $errors[$fieldName] = $this->validateField($fieldName);
      }
    }
    return $errors;
  }

  public function validateField($fieldName) {
    // pas de validator
    if (!isset($this->validators[$fieldName])) {
      return false;
    }
    $validator = $this->validators[$fieldName];
    $validatorFunc = $validator[0];
    $validatorParams = isset($validator[1]) ? $validator[1] : null;
    // generic presence validation
    if (($presenceError = Validator::presence($fieldName, $this->$fieldName, $validatorParams)) != false) {
      return $presenceError;
    } else {
      // specific validation
      return call_user_func_array($validatorFunc, array($fieldName, $this->$fieldName, $validatorParams));
    }
    return false;
  }

}