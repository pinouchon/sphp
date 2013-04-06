<?php

class App extends \Slim\Slim {

  private static $_instance = null;
  private $context;
  public $fb_app_id;
  public $fb_app_secret;
  public $env;
  public $metaDescription;
  public $title;

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

    $this->configure();
  }

  public function configure() {
    if ($_SERVER['HTTP_HOST'] == "localhost.sharewizz.com" || $_SERVER['HTTP_HOST'] == "sharewizz.pinouchon.com") {
      // config local
      error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
      $this->env = 'local';
      
      ORM::configure('mysql:host=localhost;dbname=sphp');
      ORM::configure('username', 'root');
      ORM::configure('password', '');
      
      $this->fb_app_id = '';
      $this->fb_app_secret = '';
      $this->facebook_redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/';
//      TwitterActions::$CONSUMER_KEY = '';
//      TwitterActions::$CONSUMER_SECRET = '';
      //setlocale(LC_ALL, "en_EN");
      setlocale(LC_ALL, "fr_FR.utf8");
    } else if ($_SERVER['HTTP_HOST'] == "staging.sharewizz.com") {
      $this->shouldIStay();
      // config dev
      $this->env = 'dev';
      error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
      ORM::configure('mysql:host=localhost;dbname=sharewizz');
      ORM::configure('username', 'root');
      ORM::configure('password', 'pelican');

      $this->fb_app_id = '';
      $this->fb_app_secret = '';
      $this->facebook_redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/';
      TwitterActions::$CONSUMER_KEY = '';
      TwitterActions::$CONSUMER_SECRET = '';
      setlocale(LC_ALL, "fr_FR.utf8");
    } else {
      if (Utils::startsWith($_SERVER['HTTP_HOST'], 'www')) {
        header('location: http://sharewizz.com', true, 302);
        die();
      }
      //config prod
      // *.sharewizz.com
      $this->env = 'prod';
      ORM::configure('mysql:host=localhost;dbname=sharewizz');
      ORM::configure('username', 'root');
      ORM::configure('password', 'pelican');

      $this->fb_app_id = '';
      $this->fb_app_secret = '';
      $this->facebook_redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/';
      TwitterActions::$CONSUMER_KEY = '';
      TwitterActions::$CONSUMER_SECRET = '';
      setlocale(LC_ALL, "fr_FR.utf8");
    }
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
    $vars['metaDescription'] = $this->metaDescription;
    $vars['title'] = 'Sharewizz, prêt gratuit entre particuliters';
    //$vars['isAdmin'] = User::isAdmin();
    if (!Utils::startsWith($this->title, "Sharewizz") &&
            !empty($this->title)) {
      $vars['title'] = $this->title . ' - Sharewizz';
    }
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
      self::getInstance()->render($template, $templateParams);
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

  public function redirectFor($name, $params = array(), $status = 302) {
    parent::redirect(urlFor($name, $params), $status);
  }

  public function setMetaDescription($desc) {
    $this->metaDescription = $desc;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

}
