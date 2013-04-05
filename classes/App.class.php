<?php

class App extends \Slim\Slim {

  private static $_instance = null;
  private $context;

  public function __construct() {
    session_start();

    // Slim configuration
    \Slim\Slim::registerAutoloader();
    parent::__construct(array(
        'templates.path' => '.',
        'view' => new MyView(),
        'mode' => 'development',
        'debug' => true
    ));
    parent::hook('slim.before.dispatch', 'HookBeforeDispatch::run');
    parent::hook('slim.before.router', 'HookBeforeRouter::run');

    //MyView::set_layout($this->getContext() . '/layout.php');
    // Routing init
    Routing::classInclude(_PATH_ . 'frontend/modules/');

    // Idiorm and Paris configuration
    ORM::configure('mysql:host=localhost;dbname=sphp');
    ORM::configure('username', 'root');
    ORM::configure('password', '');

    error_reporting(E_ALL);
  }

  /**
   * 
   * @return App
   */
  public static function getInstance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new App();
    }
    return self::$_instance;
  }

  public function render($template_link, $vars = array()) {
    $flash = array('success' => '', 'error' => '', 'info' => '');
    $flash = array_merge($flash, $this->environment['slim.flash']->getMessages());
    $vars = array_merge($vars, array('flash' => $flash));
    parent::render($template_link, $vars);
  }

  /**
   *
   * @param string $template
   * @param array $templateParams
   * @param array $jsonParams
   */
  public static function renderJson($template, $templateParams, $jsonParams) {
    ob_start();
    if ($template != null) {
      AtSlim::render($template, $templateParams);
    }
    $html = ob_get_clean();
    echo json_encode(array_merge(array('html' => $html), $jsonParams));
    header('Content-type: application/json');
    die;
  }

  public static function template($moduleAction, $vars = array()) {
    return self::getInstance()->view()->template($moduleAction, $vars);
  }

  public function Run() {
    Routing::loadRoutes();
    parent::run();
  }

  public function setContext($context) {
    $this->context = $context;
  }

  public function getContext() {
    return $this->context;
  }

  public function get_($url, $action) {
    $routeName = lcfirst(str_replace('Actions::', '#', $action));
    parent::get($url, $action)->name($routeName);
  }

  public function post($url, $action) {
    $routeName = lcfirst(str_replace('Actions::', '#', $action));
    parent::post($url, $action)->name($routeName);
  }

}
