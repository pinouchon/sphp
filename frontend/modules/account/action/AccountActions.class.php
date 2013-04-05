<?php

class AccountActions {

    public function login($user_id) {
        //$user = Model::factory('User')->find_one($user_id);
        $user = 'tata';
        App::render('account#login', array('user' => $user));
    }

}
