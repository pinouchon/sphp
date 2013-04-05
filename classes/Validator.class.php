<?php

class Validator {

    static public $fieldLabels = array(
//        'fr' => array(
//            'username' => 'nom',
//            'email' => 'e-mail'),
//        'en' => array(
//            'username' => 'user name',
//            'email' => 'email')
    );

    public static function presence($fieldName, $fieldValue, $params = array()) {
        if (isset($params['presence']) && $params['presence'] == false) {
            return false;
        }
        if (empty($fieldValue)) {
            return "$fieldName is missing.";
        }
        return false;
    }

    public static function string($fieldName, $fieldValue, $params) {
        if (($presenceError = self::presence($fieldName, $fieldValue, $params)) != false) {
            return $presenceError;
        }
        if (!is_array($params)) {
            return false;
        }
        foreach ($params as $paramName => $validatorValue) {
            if ($paramName == 'max') {
                if (strlen($fieldValue) > $validatorValue) {
                    return sprintf("%s cannot exceed %s characters", ucfirst($fieldName), $validatorValue);
                }
            }
            if ($paramName == 'min') {
                if (strlen($fieldValue) < $validatorValue) {
                    return sprintf("%s must be at least %s characters", ucfirst($fieldName), $validatorValue);
                }
            }
        }
        return false;
    }

    public static function email($fieldName, $fieldValue, $params = null) {
        if (($presenceError = self::presence($fieldName, $fieldValue, $params)) != false) {
            return $presenceError;
        }

        if (!preg_match('/^\S+@\S+\.\S+$/', $fieldValue)) {
            return 'The email is invalid';
        }
        if (isset($params['existant']) && $params['existant'] == 1 &&
                ORM::for_table('user')->where('email', $fieldValue)->find_one()) {
            return ('This email already exists');
        }
        return false;
    }

//    public static function birthday($fieldName, $fieldValue, $params = null) {
//        if (($presenceError = self::presence($fieldName, $fieldValue, $params)) != false) {
//            return $presenceError;
//        }
//
//        list($year, $month, $day) = explode('-', $fieldValue);
//        if (!checkdate($month, $day, $year)) {
//            return 'La date est invalide';
//        }
//
//        if (isset($params['min'])) {
//            $then = strtotime($fieldValue);
//            $min = strtotime('+18 years', $then);
//            if (time() < $min) {
//                return 'Vous devez avoir au minimum ' . $params['min'] . ' ans';
//            }
//        }
//    }

    public static function integer($fieldName, $fieldValue, $params = array()) {
        if (($presenceError = self::presence($fieldName, $fieldValue, $params)) != false) {
            return $presenceError;
        }

        if (!is_numeric($fieldValue)) {
            return ucfirst($fieldName) . " must be a number.";
        }
        foreach ($params as $paramName => $validatorValue) {
            if ($paramName == 'max') {
                if ($fieldValue > $validatorValue) {
                    return sprintf("%s must be lower than %s.", ucfirst($fieldName), $validatorValue);
                }
            }
            if ($paramName == 'min') {
                if ($fieldValue < $validatorValue) {
                    return sprintf("%s must be higher than %s.", ucfirst($fieldName), $validatorValue);
                }
            }
        }
        return false;
    }

}
