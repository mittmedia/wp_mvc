<?php

namespace WpMvc
{
  class SiteMeta extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_sitemeta';
    public static $class_name = '\WpMvc\SiteMeta';
    public static $id_column = 'meta_id';

    public static function find_by_site_id( $site_id )
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE site_id = $site_id ORDER BY meta_id;";

      return self::query( $query );
    }
  }
}
