<?php

namespace WpMvc
{
  class User extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_users';
    public static $class_name = '\WpMvc\User';
    public static $id_column = 'ID';
    public $usermeta;

    public function init_relations()
    {
      static::has_many_user_meta();
    }

    private function has_many_user_meta()
    {
      $meta = UserMeta::find_by_user_id( $this->{static::$id_column} );

      foreach ( $meta as $meta_item ) {
        if (!$meta_item->meta_key)
          continue;

        $meta_item->source_object = clone $meta_item;
        $this->usermeta->{$meta_item->meta_key} = $meta_item;
      }
    }

    public static function find_by_email($email)
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE user_email = '$email' ORDER BY ID;";

      return self::query( $query );
    }
  }
}
