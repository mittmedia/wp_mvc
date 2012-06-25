<?php

namespace WpMvc
{
  class CustomFooter extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_sitemeta';
    public static $class_name = '\WpMvc\SiteMeta';
    public static $id_column = 'meta_id';
    public $options;

    public function init()
    {
      #static::has_many_options();
    }

    private function has_many_options()
    {
      $options = Option::find_by_blog_id( $this->{static::$id_column} );

      foreach ( $options as $option ) {
        $this->options->{$option->option_name} = $option;
      }
    }
  }
}

