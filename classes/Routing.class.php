<?php

class Routing {

    public function classInclude($dirname) {
        // Modules class include
        foreach (scandir($dirname) as $currentDir) {
            if (is_dir($dirname . $currentDir) && $currentDir[0] != '.') {
                foreach (glob("$dirname/$currentDir/*.class.php") as $file) {
                    require_once ($file);
                }
            }
        }
    }

    public static function loadRoutes() {
        $app = App::getInstance();

        // homepage module
        $app->get_('/', "HomepageActions::homepage");

        // topic module
        $app->get_('/topic/create', "TopicActions::createGet");
        $app->post('/topic/create', "TopicActions::createPost");
        $app->get_('/topic/:id', "TopicActions::indexGet");

        // account module
        $app->get_('/login/:user_id', "AccountActions::login");
    }

}