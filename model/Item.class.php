<?php

/**
 * Fields:
 * @property int $id
 * @property String $name
 * @property String $description
 * @property String $status
 * @property String $visibility
 * @property int $caution
 * @property int $has_fb_tags 1: fb tags has been imported
 * @property int $category_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 * 
 *
 * Relations:
 * @property User $owner
 * @property Borrow[] $borrows
 * @property Category $category
 * @property Tag[] $tags
 *
 */
class Item extends MyModel {

  public function __construct() {
    $this->validators['name'] = array("Validator::string", array('min' => 2, 'max' => 50));
    //$this->validators['description'] = array("Validator::string", array('min' => 0, 'max' => 1500, 'presence' => false));
    parent::__construct();
  }

  public function example() {
    return $this->has_many_through('Item', 'Like', 'user_like', 'id_type')->where('like.type', 'keyword');
  }
  
  public function owner() {
    return $this->belongs_to('User');
  }
  
  public function borrows() {
    return $this->has_many('Borrows');
  }
  
  public function category() {
    return $this->belongs_to('Category');
  }
  
  public function tags() {
    return $this->has_many_through('Tag');
  }

  public function toString() {
    return $this->name;
  }

  /**
   * @return Item
   */
  public static function factory() {
    return parent::factory();
  }

}