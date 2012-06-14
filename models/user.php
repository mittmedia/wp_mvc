<?php

class User extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_users';
  public static $class_name = 'User';
  public static $id_column = 'ID';

  public function user_meta( $key )
  {
    return \UserMeta::find_by_user_id_and_key( $this->{static::$id_column}, $key );
  }
}
