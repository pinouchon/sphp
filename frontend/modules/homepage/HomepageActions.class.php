<?php

class HomepageActions {

    public function homepage() {
        App::getInstance()->render('homepage#homepage', array('toto' => 'from h'));
    }

}