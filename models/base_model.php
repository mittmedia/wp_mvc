<?php

namespace WpMvc
{
  class BaseModel
  {
    protected $object;
    public static $id;
    public static $table_name;
    public static $class_name;
    public static $id_column;

    public static function all()
    {
      global $wpdb;

      $table = static::$table_name;
      $class = static::$class_name;

      $results = $wpdb->get_results( "SELECT * FROM $table;" );

      $all_users = array();

      foreach ( $results as $result ) {
        $return_user = new $class();
        $return_user->object = $result;

        array_push( $all_users, $return_user );
      }
      
      return $all_users;
    }

    public static function find( $id )
    {
      global $wpdb;

      $table = static::$table_name;
      $class = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE id = %s;", $id ) );

      $return_user = new $class();

      if ( $results ) {
        $return_user->object = $results[0];
      }

      return $return_user;
    }

    public static function virgin()
    {
      $class = static::$class_name;

      $return_user = new $class();
      $return_user->object = $return_user;
      return $return_user;
    }

    public static function brand_new()
    {
      $class = static::$class_name;

      $return_user = new $class();
      $return_user->object = $return_user;
      return $return_user;
    }

    public function attr( $name, $value = null )
    {
      foreach ( $this->object as $obj_key => $obj_value ) {
        if ( $obj_key == $name ) {
          if ( $value )
            $this->object->{$obj_key} = $value;

          return $obj_value;
        }
      }
    }

    public function save()
    {
      $class = strtolower( static::$class_name );

      $id = null;

      $_POST[$class][static::$id_column] > 0 ? $id = $this->update() : $id = $this->create();
    
      return $id;
    }

    private function create()
    {
      global $wpdb;

      $table = static::$table_name;
      $class = strtolower( static::$class_name );

      $wpdb->insert( $table, $_POST[$class], array() );

      return $wpdb->insert_id;
    }

    private function update() {
      global $wpdb;

      $table = static::$table_name;
      $class = strtolower( static::$class_name );

      $id = $_POST[$class][static::$id_column];
      unset( $_POST[$class][static::$id_column] );

      $wpdb->update(
        $table,
        $_POST[$class],
        array(
          static::$id_column => $id
        ), 
        array(), 
        array() 
      );

      return $id;
    }
  }
}
