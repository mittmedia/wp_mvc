<?php

class Blog extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_blogs';
  public static $class_name = 'Blog';
  public static $id_column = 'blog_id';
  public $options;

  public function init()
  {
    self::has_many( $this->options, \Option::find_by_blog_id( $this->{static::$id_column} ) );
  }
}
