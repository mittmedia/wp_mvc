<?php

class Option extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_options';
  public static $class_name = 'Option';
  public static $id_column = 'option_id';

  public static function find_by_blog_id( $blog_id )
  {
    if ( $blog_id != 1 )
      $this->table_name = "wp_{$blog_id}_options";

    global $wpdb;

    $table = static::$table_name;

    $query = "SELECT * FROM $table;";

    return self::query( $query );
  }
}
