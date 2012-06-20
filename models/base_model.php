<?php

namespace WpMvc
{
  class BaseModel
  {
    public static $table_name;
    public static $class_name;
    public static $id_column;
    protected $db_columns;

    public function __construct()
    {
      $this->populate();
    }

    public static function all()
    {
      global $wpdb;

      $table = static::$table_name;
      $class = static::$class_name;

      $results = $wpdb->get_results( "SELECT * FROM $table;" );

      $all = array();

      foreach ( $results as $result ) {
        $return_object = new $class();

        $return_object->populate_fields( $result, $return_object );

        $return_object->class_init();

        array_push( $all, $return_object );
      }

      return $all;
    }

    public static function find( $id )
    {
      global $wpdb;

      $table = static::$table_name;
      $id_column = static::$id_column;
      $class = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $id_column = %s LIMIT 1;", $id ) );

      $return_object = new $class();

      if ( $results ) {
        $return_object->populate_fields( $results[0], $return_object );
      } else {
        trigger_error( "Couldn't find $id_column $id of $class in $table.", E_USER_ERROR );
      }

      $return_object->class_init();

      return $return_object;
    }

    public static function query( $query )
    {
      global $wpdb;

      $table = static::$table_name;
      $class = static::$class_name;

      $results = $wpdb->get_results( $query );

      $all = array();

      if ( $results ) {
        foreach ( $results as $result ) {
          $return_object = new $class();
          
          $return_object->populate_fields( $result, $return_object );

          array_push( $all, $return_object );
        }
      } else {
        trigger_error( "Nothing found on \"$query\".", E_USER_ERROR );
      }
      
      return $all;
    }

    public static function virgin()
    {
      $class = $this->class_name;

      $return_object = new $class();

      return $return_object;
    }

    public function takes_post( $post )
    {
      $key_array = array();
      $this->iterate_post_keys( $post, $key_array );
    }

    public function save()
    {
      $this->{static::$id_column} ? $id = $this->update() : $id = $this->create();

      $object_array = array();

      $this->iterate_save_keys( $this, $object_array );

      foreach ( $object_array as $object ) {
        $object->save();
      }

      return $id;
    }

    protected function populate()
    {
      global $wpdb;

      $table = static::$table_name;
      $class = strtolower( static::$class_name );

      $results = $wpdb->get_results( "SHOW COLUMNS FROM $table;" );

      $this->db_columns = $results;

      foreach ( $results as $result ) {
        if ( ! property_exists( $this, $result->Field ) ) {
          $this->{$result->Field} = null;
        }
      }
    }

    protected function populate_fields( $result, &$return_object = null )
    {
      foreach ( $result as $field => $value )
        $return_object ? $return_object->{$field} = $value : $this->{$field} = $value;
    }

    private function iterate_post_keys( $post, &$key_array )
    {
      foreach ( $post as $key => $value ) {
        if ( is_array( $value ) ) {
          array_push( $key_array, $key );
          $this->iterate_post_keys( $value, $key_array );
        } else {
          array_push( $key_array, $key );

          switch ( count( $key_array ) ) {
            case '1':
              $this->assign_array_depth1( $key_array, $value );
              break;
            case '2':
              $this->assign_array_depth2( $key_array, $value );
              break;
            case '3':
              $this->assign_array_depth3( $key_array, $value );
              break;
            case '4':
              $this->assign_array_depth4( $key_array, $value );
              break;
            case '5':
              $this->assign_array_depth5( $key_array, $value );
              break;
          }

          $key_array = array();
        }
      }
    }

    private function iterate_save_keys( $object, &$object_array )
    {
      foreach ( $object as $object_item ) {
        if ( is_object( $object_item ) ) {
          if ( method_exists( $object_item, 'save' ) )
            array_push( $object_array, $object_item );

          $this->iterate_save_keys( $object_item, $object_array );
        }
      }
    }

    private function assign_array_depth1( $keys, $value ) { $this->{$keys[0]} = $value; }
    private function assign_array_depth2( $keys, $value ) { $this->{$keys[0]}->{$keys[1]} = $value; }
    private function assign_array_depth3( $keys, $value ) { $this->{$keys[0]}->{$keys[1]}->{$keys[2]} = $value; }
    private function assign_array_depth4( $keys, $value ) { $this->{$keys[0]}->{$keys[1]}->{$keys[2]}->{$keys[3]} = $value; }
    private function assign_array_depth5( $keys, $value ) { $this->{$keys[0]}->{$keys[1]}->{$keys[2]}->{$keys[3]}->{$keys[3]} = $value; }

    private function class_init()
    {
      if ( method_exists( $this, 'init' ) )
        $this->init();
    }

    private function create()
    {
      global $wpdb;

      $table = $this->table_name;
      $class = strtolower( $this->class_name );

      $wpdb->insert( $table, $this->as_db_array(), array() );

      return $wpdb->insert_id;
    }

    private function update()
    {
      global $wpdb;

      $table = static::$table_name;
      $class = strtolower( static::$class_name );
      $id = $this->{static::$id_column};

      $wpdb->update(
        $table,
        $this->as_db_array(),
        array(
          static::$id_column => $id
        ), 
        array(), 
        array() 
      );

      return $id;
    }

    private function as_db_array()
    {
      $return_array = array();

      foreach ( $this->db_columns as $db_column ) {
        if ( $this->{$db_column->Field} || $this->{$db_column->Field} == 0 ) {
          $return_array[$db_column->Field] = $this->{$db_column->Field};
        }
      }

      return $return_array;
    }
  }
}
