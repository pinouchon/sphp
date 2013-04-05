<?php

class HookBeforeRouter {

    static private function defineContext() {
        $urlPath = App::getInstance()->request()->getPath();
        if (Utils::startsWith($urlPath, '/admin/')) {
            App::getInstance()->setContext('backend');
        } else {
            App::getInstance()->setContext('frontend');
        }
    }

    static function run() {
        self::defineContext();
        MyView::set_layout(App::getInstance()->getContext() . '/layout.php');
    }

}