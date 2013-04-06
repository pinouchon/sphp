<?php

class HomepageActions {

  public function homepage() {
    //App::getInstance()->flashNow('info', 'héhé<br/>sfs fsdfsdf sd<br/>s');
    App::getInstance()->render('homepage#homepage', array('toto' => 'from h'));
  }

}