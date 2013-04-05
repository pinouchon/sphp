<?php

class MyView extends \Slim\View {

    static protected $_layout = NULL;

    public static function set_layout($layout = NULL) {
        self::$_layout = $layout;
    }
    
    public function getTemplatePath($moduleAction) {
        $explodedModuleAction = explode('#', $moduleAction);
        if (!isset($explodedModuleAction[0]) || !isset($explodedModuleAction[1])) {
            throw new RuntimeException('The route `' . $moduleAction . '` is incorect. It must be module#action.');
        }
        list($module, $action) = $explodedModuleAction;
        
        $path = App::getInstance()->getContext();
        if ($module != 'global') {
            $path .= "/modules/$module/views/$action.php";
        } else {
            $path .= "/components/_$action.php";
        }
        return $path;
    }

    public function render($moduleAction) {
        extract($this->data);
        //$templatePath = $this->getTemplatesDirectory() . '/' . ltrim($template, '/');
        $templatePath = $this->getTemplatePath($moduleAction);
        if (!file_exists($templatePath)) {
            throw new RuntimeException('View cannot render template `' . $templatePath . '`. Template does not exist.');
        }
        ob_start();
        require $templatePath;
        $html = ob_get_clean();
        return $this->_render_layout($html);
    }

    public function template($moduleAction, $vars = array()) {
        extract($this->data);
        ob_start();
        if (count($vars) > 0) {
            extract($vars);
        }
        include $this->getTemplatePath($moduleAction);
        return ob_get_clean();
    }

    public function _render_layout($_html) {
        extract($this->data);
        
        if (self::$_layout !== NULL) {
            $layout_path = $this->getTemplatesDirectory() . '/' . ltrim(self::$_layout, '/');
            if (!file_exists($layout_path)) {
                throw new RuntimeException('View cannot render layout `' . $layout_path . '`. Layout does not exist.');
            }
            ob_start();
            require $layout_path;
            $_html = ob_get_clean();
        }
        return $_html;
    }

}
