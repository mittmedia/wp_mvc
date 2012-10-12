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

    public static function all($get_relations = true, $table_name = "")
    {
      global $wpdb;

      if ($table_name == "")
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

        $return_object->source_object = clone $return_object;
        $return_object->__db_table = $table_name;

        array_push( $all, $return_object );
      }

      return $all;
    }

    public static function find_by_name($name, $get_relations = true, $table_name = "")
    {
      global $wpdb;

      if ($table_name == "")
        $table_name = static::$table_name;

      $name_column = static::$name_column;
      $class_name = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE $name_column = %s LIMIT 1;", $name ) );

      $return_object = new $class_name();

      if ( $results ) {
        $return_object->populate_fields( $results[0], $return_object );
      } else {
        throw new \Exception( "Couldn't find $id_column $id of $class_name in $table_name.", E_USER_ERROR );
      }

      if ( $get_relations ) {
        $return_object->init_class_relations();
      }

      $return_object->source_object = clone $return_object;
      $return_object->__db_table = $table_name;

      return $return_object;
    }

    public static function find($id, $get_relations = true, $table_name = "")
    {
      global $wpdb;

      if ($table_name == "")
        $table_name = static::$table_name;

      $id_column = static::$id_column;
      $class_name = static::$class_name;

      $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE $id_column = %s LIMIT 1;", $id ) );

      $return_object = new $class_name();

      if ( $results ) {
        $return_object->populate_fields( $results[0], $return_object );
      } else {
        return false;
      }

      if ( $get_relations ) {
        $return_object->init_class_relations();
      }

      $return_object->source_object = clone $return_object;
      $return_object->__db_table = $table_name;

      return $return_object;
    }

    public static function query($query, $get_relations = true, $table_name = "")
    {
      global $wpdb;

      if ($table_name == "")
        $table_name = static::$table_name;

      $class_name = static::$class_name;

      $results = $wpdb->get_results( $query );

      $all = array();

      if ( $results ) {
        foreach ( $results as $result ) {
          $return_object = new $class_name();

          $return_object->populate_fields( $result, $return_object );

          if ( $get_relations ) {
            $return_object->init_class_relations();
          }

          $return_object->source_object = clone $return_object;
          $return_object->__db_table = $table_name;

          array_push( $all, $return_object );
        }
      } else {
        return false;
      }

      return $all;
    }

    public static function virgin($table_name = "")
    {
      if ($table_name == "")
        $table_name = static::$table_name;

      $class_name = static::$class_name;

      $return_object = new $class_name();

      $return_object->source_object = clone $return_object;
      $return_object->__db_table = $table_name;

      return $return_object;
    }

    public function takes_post( $post )
    {
      $key_array = array();
      $depth = 0;
      $this->iterate_post_keys_and_populate( $post, $key_array, $depth );
    }

    public function save()
    {
      $object_array = array();

      $this->iterate_object_for_method_save( $this, $object_array );

      foreach ( $object_array as $object ) {
        $object->save();
      }

      if ( isset( $this->delete_action ) ) {
        $this->delete();
      } else {
        $this->validate();

        $this->{static::$id_column} ? $id = $this->update() : $id = $this->create();

        return $id;
      }
    }

    public function delete()
    {
      global $wpdb;

      $table_name = static::$table_name;
      $id_column = static::$id_column;
      $id = $this->{static::$id_column};

      $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE $id_column = %s", $id ) );

      return $id_column;
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


    private function iterate_post_keys_and_populate( $post, &$key_array, $depth )
    {
      foreach ( $post as $post_key => $post_value ) {
        if( is_array( $post_value ) ) {
          $depth++;

          if ( isset( $post_value['delete_action'] ) ) {
            $post_value['delete_action'] = true;
          }

          array_push( $key_array, $post_key );

          $this->iterate_post_keys_and_populate( $post_value, &$key_array, $depth );

          array_pop( $key_array );
        } else {
          array_push( $key_array, $post_key );

          switch ( count( $key_array ) ) {
            case 0:
              echo "1";
              break;
            case 1:
              echo "2";
              break;
            case 2:
              echo "3";
              break;
            case 3:
              $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]} = $post_value;
              break;
            case 4:
              $this->{$key_array[0]}->{$key_array[1]}->{$key_array[2]}->{$key_array[3]}  = $post_value;
              break;
            case 4:
              break;
            case 5:
              break;
          }

          array_pop( $key_array );
        }
      }

      $depth = 0;
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

    public function init_class_relations()
    {
      if ( method_exists( $this, 'init_relations' ) )
        $this->init_relations();
    }

    private function create()
    {
      global $wpdb;

      $table_name = static::$table_name;
      if (isset($this->__db_table))
        $table_name = $this->__db_table;

      $result = $wpdb->insert( $table_name, $this->as_db_array(), array() );

      \WpMvc\DevHelper::dump($result);

      return $wpdb->insert_id;
    }

    private function update()
    {
      global $wpdb;

      $table_name = static::$table_name;
      if (isset($this->__db_table))
        $table_name = $this->__db_table;
      $id_column = static::$id_column;
      $id = $this->{static::$id_column};

      $columns_array = $this->as_db_array();

      if (is_array($columns_array) && count($columns_array) > 0) {
        $result = $wpdb->update(
          $table_name,
          $columns_array,
          array(
            $id_column => $id
          )
        );

        return $id;
      } else {
        return false;
      }
    }

    private function as_db_array()
    {
      $return_array = array();

      foreach ( $this->db_columns as $db_column ) {
        $backtrace = debug_backtrace();

        var_dump($backtrace[1]["function"]);

        if ($backtrace[1]["function"] != "create" && !isset($this->source_object->{$db_column->Field})) {
          continue;
        }

        if ($backtrace[1]["function"] != "create" && $this->{$db_column->Field} == $this->source_object->{$db_column->Field}) {
          continue;
        }

        if ($this->{$db_column->Field} || $this->{$db_column->Field} == 0)
          $return_array[$db_column->Field] = $this->{$db_column->Field};
      }

      return $return_array;
    }
  }
}
