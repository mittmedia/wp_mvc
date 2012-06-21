<?php

class UserMeta extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_usermeta';
  public static $class_name = 'UserMeta';
  public static $id_column = 'umeta_id';

  public static function find_by_user_id( $user_id )
  {
    global $wpdb;

    $table_name = static::$table_name;

    $query = "SELECT * FROM $table_name WHERE user_id = $user_id;";

    return self::query( $query );
  }
}
