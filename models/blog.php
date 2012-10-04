<?php

namespace WpMvc
{
  class Blog extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_blogs';
    public static $class_name = '\WpMvc\Blog';
    public static $id_column = 'blog_id';
    public static $name_column = 'path';
    public $option;

    public function init_relations()
    {
      $options = Option::find_by_blog_id( $this->{static::$id_column} );

      foreach ( $options as $option ) {
        if ($option->option_name)
          $this->option->{$option->option_name} = $option;
      }
    }

    public static function all_public( $get_relations = true )
    {
      global $wpdb;

      $table_name = static::$table_name;
      $class_name = static::$class_name;

      $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE public = 1 AND deleted = 0;" );

      $all = array();

      foreach ( $results as $result ) {
        $return_object = new $class_name();

        $return_object->populate_fields( $result, $return_object );

        if ( $get_relations ) {
          $return_object->init_class_relations();
        }

        array_push( $all, $return_object );
      }

      return $all;
    }

    public static function find_by_path($path)
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE path = '$path' ORDER BY blog_id;";

      return self::query( $query );
    }

    public static function find_public_by_path($path)
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE public = 1 AND deleted = 0 AND path = '$path' ORDER BY blog_id;";

      return self::query( $query );
    }

    public static function find_public($id)
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE blog_id = $id AND public = 1 AND deleted = 0 ORDER BY blog_id;";

      return self::query( $query );
    }

    public static function find_public_recently_updated($num = 20)
    {
      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE public = 1 AND deleted = 0 ORDER BY last_updated DESC LIMIT $num;";

      return self::query( $query );
    }

    public static function find_public_recently_created($num = 20)
    {
      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE public = 1 AND deleted = 0 ORDER BY registered DESC LIMIT $num;";

      return self::query( $query );
    }

    public static function find_recently_updated($num = 20)
    {
      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name ORDER BY last_updated DESC LIMIT $num;";

      return self::query( $query );
    }

    public static function find_recently_created($num = 20)
    {
      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name ORDER BY registered DESC LIMIT $num;";

      return self::query( $query );
    }
  }
}

