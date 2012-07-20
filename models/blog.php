<?php

namespace WpMvc
{
  class Blog extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_blogs';
    public static $class_name = '\WpMvc\Blog';
    public static $id_column = 'blog_id';
    public static $name_column = 'path';
    public $options;

    public function init_relations()
    {
      $options = Option::find_by_blog_id( $this->{static::$id_column} );

      foreach ( $options as $option ) {
        if ($option->option_name)
          $this->options->{$option->option_name} = $option;
      }
    }

    public static function find_by_path($path)
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE path = '$path' ORDER BY blog_id;";

      return self::query( $query );
    }
  }
}

