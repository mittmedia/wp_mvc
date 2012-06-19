<?php

class UserMeta extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_usermeta';
  public static $class_name = 'UserMeta';
  public static $id_column = 'umeta_id';

  public static function find_by_user_id_and_key( $user_id, $key )
  {
    global $wpdb;

    $table = $this->table_name;

    $return_object = self::query( $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %s AND meta_key = %s LIMIT 1;", $user_id, $key ) );

    return $return_object[0]->meta_value;
  }
}
