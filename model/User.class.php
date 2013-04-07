<?php

/**
 * Fields:
 * @property int $id
 * @property String $firstname
 * @property String $lastname
 * @property String $email
 * @property String $password
 * @property String $birthdate
 * @property String $gender
 * @property int $address_id
 * @property String $phone
 * @property String $image
 * @property int $fb_uid
 * @property int $nb_connexion
 * @property String $ip
 * @property String $has_fb_tags 1: fb tags has been imported
 * 
 * @property int $banned_by
 * @property int $ban_reason
 * @property Datetime $banned_at
 * @property DateTime $last_logged_at
 * @property DateTime $fb_created_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $activated_at
 * @property DateTime $deleted_at
 * 
 *
 * Relations:
 * @property Item[] $items
 * @property Address $address
 * @property Borrow[] $borrows
 * @property Borrow[] $loans
 * @property User[] $friends
 * @property User[] $friendRequestsSent
 * @property User[] $friendRequestsReceived
 * @property Tag[] $tags
 *
 */
class User extends MyModel {

  public function __construct() {
    $this->validators['name'] = array("Validator::string", array('min' => 2, 'max' => 50));
    $this->validators['lastname'] = array("Validator::string", array('min' => 2, 'max' => 50));
    $this->validators['email'] = array("Validator::email", array('existant' => 1));
    $this->validators['birthdate'] = array("Validator::birthday", array('min' => 18));
    parent::__construct();
  }

  public function example() {
    return $this->has_many_through('Item', 'Like', 'user_like', 'id_type')->where('like.type', 'keyword');
  }
  
  public function items() {
    return $this->has_many('Item');
  }
  
  public function address() {
    return $this->has_one('Address');
  }
  
  public function borrows() {
    return $this->has_many('Borrow', 'user_id_borrower');
  }
  
  public function loans() {
    return $this->has_many('Borrow', 'user_id_lender');
  }
  
  public function friends() {
    return $this->has_many_through('User', 'Friend', 'user_id_from', 'user_id_to')->where('status', 'accepted');
  }
  
  public function friendRequestsSent() {
    return $this->has_many_through('User', 'Friend', 'user_id_from', 'user_id_to')->where('status', 'pending');
  }
  
  public function friendRequestsReceived() {
    return $this->has_many_through('User', 'Friend', 'user_id_to', 'user_id_from')->where('status', 'pending');
  }
  
  public function tags() {
    return $this->has_many_through('Tag');
  }

  public function toString($verbose = false) {
    if ($verbose) {
      $dob = new DateTime($this->dob);
      $now = new DateTime("now");
      return "{$this->fullname()}, {$dob->diff($now)->y} ans";
    }
    return $this->fullname();
  }

  /**
   * @return User
   */
  public static function factory() {
    return parent::factory();
  }

  public function getImage($size = "52x52", $facebookSize = 'large') {
//        $id = $this->id;
//
//        if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/img/picprofil/{$id}/{$size}.jpg")) {
//            return ("/img/picprofil/{$id}/{$size}.jpg");
//        }
//        if ($this->fb_uid != null) {
//            return "http://graph.facebook.com/$this->fb_uid/picture?type=$facebookSize";
//        }
//        return ("/img/picprofil/{$size}.jpg");
  }

  public function getAge() {
    $user_birth = new DateTime();
    $user_birth->setTimestamp(strtotime($this->dob));
    return Utils::dateDiff($user_birth, new DateTime('now'), 'y');
  }

  public static function addFbInterestsInTags() {
    try {
      $connectionName = array('me/music', '/me/books', '/me/movies', '/me/games', '/me/activities', '/me/interests');
      if (User::getAuthUserObj()->has_tag_from_fb == 0) {
        // we set the flag to true to import tags from facebook only once
        $user = Model::factory('User')->find_one(User::getAuthUserObj()->id);
        $user->has_tag_from_fb = 1;
        $user->save();

        // we add the tags
        foreach ($connectionName as $connection) {
          $data = AtSlim::getFacebook()->api($connection);
          foreach ($data['data'] as $row) {
            TagActions::_addTag(User::getAuthUserObj()->id, $row['name']);
          }
        }
      }
    } catch (Exception $e) {
      // do nothing
    }
  }

  /**
   *
   * @return boolean
   */
  public static function logout() {
    if (User::isAuthenticated()) {
      session_destroy();
      session_start();
      session_regenerate_id(true);
      AtSlim::getApp()->flash('notice', 'Vous avez été déconnecté.');
      return (true);
    }
    return (false);
  }

  private static function _authenticate($user) {
    $_SESSION['auth_user'] = User::factory()->find_one($user->id);
    if (isset($_SESSION['redirect_after_login'])) {
      $redirect = $_SESSION['redirect_after_login'];
      unset($_SESSION['redirect_after_login']);
      App::getInstance()->redirectFor($redirect);
    }
    return ($user);
  }

  /**
   *
   * @return boolean
   */
  public static function isAuthenticated() {
    if (isset($_SESSION['auth_user']))
      return true;
    return false;
  }

  public static function getAuthUser() {
    if (self::isAuthenticated())
      return $_SESSION['auth_user'];
    return null;
  }

  public static function getUsersAround($latidude, $longitude, $distance, $where = '') {
    $query = "SELECT
             u.id, u.name, u.lastname, u.description,
             google_assign.name AS location,
             get_distance_metres($latidude,$longitude,google_assign.latitude, google_assign.longitude) AS distance,
             COUNT(like.id) as nb_likes

             FROM user u
             LEFT JOIN google_assign ON google_assign.id = u.city
             LEFT JOIN `like` ON type = 'user' AND like.id_type = u.id
             WHERE 1 $where
             GROUP BY u.id
             HAVING distance >= 0 AND distance < $distance
             ORDER BY nb_likes DESC, distance ASC
             LIMIT 0, 5000";

    $usersAround = ORM::for_table('')->raw_query($query, array())->find_many();
    return $usersAround;
  }

  public function getUrlSlug() {
    return Utils::slugify($this->fullname());
  }

  public function fullname() {
    return "$this->firstname $this->lastname";
  }
  
  public static function isActivated() {
    return false;
  }
  
  public static function has3objets() {
    return false;
  }

}