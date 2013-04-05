<?php

class TopicActions {

    public function indexGet() {
        App::getInstance()->render('homepage#homepage', array('toto' => 'from h'));
    }

    public function createGet() {
        //App::getInstance()->flashNow('success', 'hello world');
        //App::getInstance()->flashNow('error', array('title' => 'You have an error', 'body' => ' f dsf sdfdsf sdfsdfs'));
        App::getInstance()->render('topic#create', array('toto' => 'from h'));
    }

    public function createPost() {
        var_dump($_POST);
        $topic = Model::factory('Topic')->create();
        $topic->populate($_POST);

        $errors = $topic->validate();
        echo "errors:";
        var_dump($errors);
        die('creating');
        //Quicksort::getInstance()->render('topic#createGet', array('toto' => 'from h'));
    }

}