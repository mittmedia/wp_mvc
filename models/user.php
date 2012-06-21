<?php

class User extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_users';
  public static $class_name = 'User';
  public static $id_column = 'ID';
  public $usermeta;

  public function init()
  {
    static::has_many_user_meta();
  }

  private function has_many_user_meta()
  {
    $meta = \UserMeta::find_by_user_id( $this->{static::$id_column} );

    foreach ( $meta as $meta_item ) {
      $this->usermeta->{$meta_item->meta_key} = $meta_item;
    }
  }
}
