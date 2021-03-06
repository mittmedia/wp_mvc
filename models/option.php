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
      $table_name = static::$table_name;

      if ( $blog_id != 1 )
        $table_name = "wp_{$blog_id}_options";

      global $wpdb;

      $query = "SELECT * FROM $table_name;";

      return self::query($query, false, $table_name);
    }

    public static function find_by_blog_id_and_option_name($blog_id, $option_name)
    {
      $table_name = static::$table_name;

      if ( $blog_id != 1 )
        $table_name = "wp_{$blog_id}_options";

      global $wpdb;

      $query = "SELECT * FROM $table_name WHERE option_name = '{$option_name}';";

      return self::query($query, false, $table_name);
    }

    public static function virgin($blog_id = 1)
    {
      $table_name = static::$table_name;

      if ( $blog_id != 1 )
        $table_name = "wp_{$blog_id}_options";

      $class_name = static::$class_name;

      $return_object = new $class_name();

      $return_object->source_object = clone $return_object;
      $return_object->__db_table = $table_name;

      return $return_object;
    }
  }
}
