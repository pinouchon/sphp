<?php

/**
 * Fields:
 * @property int $id
 * @property int $user_id_borrower
 * @property int $user_id_lender
 * @property int $item_id
 * @property DateTime $created_at
 * @property DateTime $ended_at
 * 
 *
 * Relations:
 * @property User $lender
 * @property User $borrower
 * @property Item $category
 *
 */
class Borrow extends MyModel {

  public function __construct() {
    parent::__construct();
  }
  
  public function lender() {
    return $this->belongs_to('User', 'user_id_lender');
  }
  
  public function borrower() {
    return $this->belongs_to('User', 'user_id_borrower');
  }
  
  public function item() {
    return $this->belongs_to('Item');
  }

  public function toString() {
    return $this->item->name;
  }

  /**
   * @return Borrow
   */
  public static function factory() {
    return parent::factory();
  }

}