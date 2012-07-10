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
        $this->options->{$option->option_name} = $option;
      }
    }
  }
}

