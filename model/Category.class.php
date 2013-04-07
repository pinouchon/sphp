<?php

/**
 * Fields:
 * @property int $id
 * @property String $name
 * 
 *
 * Relations:
 * @property User $owner
 * @property Borrow[] $borrows
 * @property Category $category
 * @property Tag[] $tags
 *
 */
class Category extends MyModel {

  public function __construct() {
    parent::__construct();
  }
  
  public function items() {
    return $this->has_many('Item');
  }

  public function toString() {
    return $this->name;
  }

  /**
   * @return Category
   */
  public static function factory() {
    return parent::factory();
  }

}