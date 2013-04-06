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
 * @property Tag[] $tags Tags or the user
 * @property Activity[] $likedActivities
 * @property User[] $likedUsers
 * @property Place[] $likedPlaces
 * @property Activity[] $activities
 * @property ApiCache $apiCache
 * @property Address $address
 * @property Badge[] $badges
 * @property Language[] $languages
 * @property Recommendation[] $receivedRecommendations
 * @property Recommendation[] $sentRecommendations
 * @property Comment[] $comments All the user's comments
 * @property Participation[] $participations All the user's participations
 * @property Message[] $receivedMessages
 * @property Message[] $sentMessages
 * @property Place[] $places placed that the user created
 * @property Like[] $receivedLikes the likes that the user received. Use likedByUser to get User object instead of Liked object.
 * @property User[] $likedByUsers Users that liked the current user

 *
 */
class User extends MyModel {

  public function __construct() {
    $this->validators['name'] = array("Validator::string", array('min' => 2, 'max' => 50));
    $this->validators['lastname'] = array("Validator::string", array('min' => 2, 'max' => 50));
    $this->validators['email'] = array("Validator::email", array('existant' => 1));
    $this->validators['dob'] = array("Validator::birthday", array('min' => 18));
    $this->validators['description'] = array("Validator::string", array('min' => 0, 'max' => 1500, 'presence' => false));
    parent::__construct();
  }

  public function tags() {
    return $this->has_many_through('Tag', 'Like', 'user_like', 'id_type')->where('like.type', 'keyword');
  }

  public function likedActivities() {
    return $this->has_many_through('Activity', 'Like', 'user_like', 'id_type')->where('like.type', 'activity');
  }

  public function likedUsers() {
    return $this->has_many_through('User', 'Like', 'user_like', 'id_type')->where('like.type', 'user');
  }

  public function likedPlaces() {
    return $this->has_many_through('Place', 'Like', 'user_like', 'id_type')->where('like.type', 'shop');
  }

  public function activities() {
    return $this->has_many('Activity');
  }

  public function apiCache() {
    return $this->has_one('ApiCache');
  }

  public function address() {
    return $this->belongs_to('Address');
  }

  public function badges() {
    return $this->has_many_through('Badge');
  }

  public function languages() {
    return $this->has_many_through('Language');
  }

  public function receivedRecommendations() {
    return $this->has_many('Recommendation', 'user_id_to');
  }

  public function sentRecommendations() {
    return $this->has_many('Recommendation', 'user_id_from');
  }

  public function comments() {
    return $this->has_many('Comment');
  }

  public function participations() {
    return $this->has_many('Participation');
  }

  public function receivedMessages() {
    return $this->has_many('Message', 'user_id_to');
  }

  public function sentMessages() {
    return $this->has_many('Message', 'user_id_from');
  }

  public function places() {
    return $this->has_many('Place');
  }

  public function receivedLikes() {
    return $this->has_many('Like', 'id_type')->where('Like.type', 'user');
  }

  public function likedByUsers() {
    return $this->has_many_through('User', 'Like', 'id_type', 'user_like')->where('like.type', 'user');
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
   * @param string $email
   * @param string $password
   * @return User
   */
  public static function authenticate($email, $password) {
    $user = ORM::for_table('user')->where('email', $email)->where('password', md5($password . 'M@g1c.T0k3n'))->find_one();
    if ($user->loaded()) {
      if ($user->actif == 1 && $user->ban == 0) {
        return self::_authenticate($user);
      } else {
        //Si le membre est banni
        if ($user->ban == 1) {
          AtSlim::getApp()->flash('error', 'Votre compte a été banni, vous ne pouvez pas vous connecter actuellement.');
          AtSlim::redirectFor('homepage#homepageGet');
        }
        //Si le membre n'a pas encore activé son compte
        elseif ($user->actif == 0) {
          AtSlim::getApp()->flash('error', 'Votre compte n\'a pas été activé, merci de procéder à l\'activation de ce dernier pour pouvoir vous connecter');
          AtSlim::redirectFor('homepage#homepageGet');
        }
      }
    } else {
      return (false);
    }
  }

  public static function forceAuthenticate($id) {
    $user = ORM::for_table('user')->where('id', $id)->find_one();
    if ($user != false) {
      return self::_authenticate($user);
    }
    return (false);
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

  public static function getAuthUserObj() {
    if (self::isAuthenticated()) {
      // todo: change that
      return User::factory()->find_one($_SESSION['auth_user']['id']);
      return $_SESSION['auth_user_obj'];
    }
//        $_SESSION['redirect_after_login'] = $_SESSION['PHP_SELF'];
//        AtSlim::getApp()->flash('error', 'Vous devez être connecté pour aller sur cette page');
//        AtSlim::redirectFor('login#loginGet');
    return null;
  }

  public static function getAuthUserName() {
    if (!self::isAuthenticated()) {
      return 'Visiteur';
    }
    $user = self::getAuthUserObj();
    return ucfirst($user->name) . ' ' . ucfirst($user->lastname);
  }

  /**
   * Check if the user is authenticated. If he is not, prompt for login, and redirects to
   * $redirect after login. If the user is authenticated, returns the auth_user.
   * @param string $redirect, ex: homepage#homepageGet
   * @return array, the auth user as array
   */
  public static function checkAuth($redirect = 'homepage#homepageGet', $message = 'Vous devez vous connecter pour aller sur cette page') {
    if (self::isAuthenticated() == false) {
      AtSlim::getApp()->flash('error', $message);
      $_SESSION['redirect_after_login'] = $redirect;
//            if (isset($_SERVER['HTTP_REFERER'])) {
//                AtSlim::getApp()->redirect($_SERVER['HTTP_REFERER']);
//            }
      AtSlim::redirectFor('account#registerGet');
      // pop the login box
    }
    return self::getAuthUser();
  }

  /**
   * Get the friend of a given user id (like, type user=friend)
   * Use like this in twig: {{ friend.name }} {{ friend.lastname }} {{ friend.location }}
   * @param type $userId
   * @return type
   */
  public static function friendsOf($userId) {
    return ORM::for_table('user')
                    ->select('friend.*')->select('google_assign.name', 'location')
                    ->join('like', 'l.user_like = user.id AND type="user"', 'l')
                    ->join('user', 'friend.id = l.id_type', 'friend')
                    ->join('google_assign', 'google_assign.id = friend.city')
                    ->where_equal('user.id', $userId)
                    ->where_not_equal('friend.id', $userId)
                    ->group_by('friend.id')
                    ->find_many();
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

  public static function checkUserId($userId) {
    $user = ORM::for_table('User')->select('id')->where_equal('user.id', $userId)->find_one();
    return $user != false;
  }

  public static function isAdmin() {
    if (!self::isAuthenticated()) {
      return false;
    }
    return in_array(User::getAuthUserObj()->id, array(
        518, // guillaume
        119, // arnaud
        5, // thibaut
        243, // benjamin
        113, // antoine
        1, // fred
        93 // allan
    ));
  }

  public function getUrlSlug() {
    return Utils::slugify(Utils::formatName($this->name, $this->lastname));
  }

  public function fullname() {
    return Utils::formatName($this->firstname, $this->lastname);
  }

}