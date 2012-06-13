<?php

namespace WpMvc
{
  class BaseModel
  {
    protected $id = null;
    protected $attributes;

    public function __construct( $attributes = null )
    {
      if ( isset( $attributes["id"] ) ) {
        $this->id = $attributes["id"];
        unset( $attributes["id"] );
      }

      //$this->attributes( $attributes );
    }

    public function find( $id )
    {
      global $wpdb;

      $user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE id = %s;", $id ) );

      return $user[0];
    }

    /*public static function find( $id )
    {
      $table = static::$table_name;

      $result = $wpdb->select( $wpdb->prepare("select * from $table where id = " . intval( $id ) ) );
      
      if ( $result ) {
        $u = new static($result->getNext());
        return($u);
      } else {
        throw new Exception($conn->getError());
      }
    }*/
  }
}
