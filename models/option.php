<?php

namespace WpMvc
{
  class Option extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_options';
    public static $class_name = '\WpMvc\Option';
    public static $id_column = 'option_id';

    public static function find_by_blog_id( $blog_id )
    {
      if ( $blog_id != 1 )
        static::$table_name = "wp_{$blog_id}_options";

      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name;";

      return self::query( $query );
    }
  }

}
