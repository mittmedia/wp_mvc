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

    public static function all( $get_relations = true )
    {
      global $wpdb;

      $table_name = static::$table_name;
      $class_name = static::$class_name;

      $results = $wpdb->get_results( "SELECT * FROM $table_name;" );

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

    public static function find( $id, $get_relations = true )
    {
      global $wpdb;

      $table_name = static::$table_name;
      $id_column = static::$id_column;
      $class_name = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE $id_column = %s LIMIT 1;", $id ) );

      $return_object = new $class_name();

      if ( $results ) {
        $return_object->populate_fields( $results[0], $return_object );
      } else {
        trigger_error( "Couldn't find $id_column $id of $class in $table.", E_USER_ERROR );
      }

      if ( $get_relations ) {
        $return_object->init_class_relations();
      }

      return $return_object;
    }

    public static function query( $query )
    {
      global $wpdb;

      $class_name = static::$class_name;

      $results = $wpdb->get_results( $query );

      $all = array();

      if ( $results ) {
        foreach ( $results as $result ) {
          $return_object = new $class_name();
          
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
      $class_name = static::$class_name;

      $return_object = new $class_name();

      return $return_object;
    }

    public function takes_post( $post )
    {
      $key_array = array();
      $this->iterate_post_keys_and_populate( $post, $post, $key_array );
    }

    public function save()
    {
      $this->validate();

      $this->{static::$id_column} ? $id = $this->update() : $id = $this->create();

      $object_array = array();

      $this->iterate_object_for_method_save( $this, $object_array );

      foreach ( $object_array as $object ) {
        $object->save();
      }

      return $id;
    }

    protected function validate()
    {
      if ( method_exists( $this, 'validate_uniqueness' ) )
        $this->validate_uniqueness();
    }

    protected function populate()
    {
      global $wpdb;

      $table_name = static::$table_name;

      $results = $wpdb->get_results( "SHOW COLUMNS FROM $table_name;" );

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

    private function iterate_post_keys_and_populate( $post, $maintain_post, &$key_array )
    {
      foreach ( $post as $post_key => $post_value ) {
        if ( is_array( $post_value ) ) {
          if ( count( $post_value ) == 0 )
            return;

          array_push( $key_array, $post_key );

          $this->iterate_post_keys_and_populate( $post_value, $maintain_post, $key_array );
        } else {
          array_push( $key_array, $post_key );

          $key_array_length = count( $key_array );

          switch ( $key_array_length ) {
            case '2':
              $this->assign_array_depth2( $key_array, $maintain_post, $post_value );
              break;
            case '3':
              $this->assign_array_depth3( $key_array, $maintain_post, $post_value );
              break;
            case '4':
              $this->assign_array_depth4( $key_array, $maintain_post, $post_value );
              break;
            case '5':
              $this->assign_array_depth5( $key_array, $maintain_post, $post_value );
              break;
            case '6':
              $this->assign_array_depth6( $key_array, $maintain_post, $post_value );
              break;
          }

          if ( $key_array_length > 2 )
            $this->iterate_post_keys_and_populate( $maintain_post, $maintain_post, $key_array );
        }
      }
    }

    private function assign_array_depth2( &$key_array, &$maintain_post, $value )
    {
      unset( $maintain_post[$key_array[0]] );
      $this->{$key_array[0]}->{$key_array[1]} = $value;
      $key_array = null;
      $key_array = array();
    }

    private function assign_array_depth3( &$key_array, &$maintain_post, $value )
    {
      unset( $maintain_post[$key_array[0]][$key_array[1]] );
      $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]} = $value;
      $key_array = null;
      $key_array = array();
    }

    private function assign_array_depth4( &$key_array, &$maintain_post, $value )
    {
      unset( $maintain_post[$key_array[0]][$key_array[1]][$key_array[2]] );
      $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]}->{$key_array[3]} = $value;
      $key_array = null;
      $key_array = array();
    }

    private function assign_array_depth5( &$key_array, &$maintain_post, $value )
    {
      unset( $maintain_post[$key_array[0]][$key_array[1]][$key_array[2]][$key_array[3]] );
      $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]}->{$key_array[3]}->{$key_array[4]} = $value;
      $key_array = null;
      $key_array = array();
    }

    private function assign_array_depth6( &$key_array, &$maintain_post, $value )
    {
      unset( $maintain_post[$key_array[0]][$key_array[1]][$key_array[2]][$key_array[3]][$key_array[4]] );
      $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]}->{$key_array[3]}->{$key_array[4]}->{$key_array[5]} = $value;
      $key_array = null;
      $key_array = array();
    }

    private function iterate_object_for_method_save( $object, &$object_array )
    {
      foreach ( $object as $object_item ) {
        if ( is_object( $object_item ) ) {
          if ( method_exists( $object_item, 'save' ) )
            array_push( $object_array, $object_item );

          $this->iterate_object_for_method_save( $object_item, $object_array );
        }
      }
    }

    private function init_class_relations()
    {
      if ( method_exists( $this, 'init_relations' ) )
        $this->init_relations();
    }

    private function create()
    {
      global $wpdb;

      $table_name = static::$table_name;

      $wpdb->insert( $table_name, $this->as_db_array(), array() );

      return $wpdb->insert_id;
    }

    private function update()
    {
      global $wpdb;

      $table_name = static::$table_name;
      $id_column = $this->{static::$id_column};

      $wpdb->update(
        $table_name,
        $this->as_db_array(),
        array(
          static::$id_column => $id_column
        ), 
        array(), 
        array() 
      );

      return $id_column;
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
