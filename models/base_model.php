<?php

namespace WpMvc
{
  class BaseModel
  {
    protected $object;
    public static $table_name;
    public static $class_name;

    public static function find( $id )
    {
      global $wpdb;

      $table = static::$table_name;
      $class = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE id = %s;", $id ) );

      if ( $results ) {
        $return_user = new $class();
        $return_user->object = $results[0];
        return $return_user;
      }

      return false;
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
      
    }
  }
}
