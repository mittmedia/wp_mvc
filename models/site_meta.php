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

      return self::query( $query, false );
    }

    public static function find_by_meta_key( $meta_key )
    {
      global $wpdb;

      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE meta_key = '$meta_key' ORDER BY meta_id;";

      return self::query( $query, false );
    }

    public static function find_highlighted_blogs()
    {
      $table_name = static::$table_name;

      $query = "SELECT * FROM $table_name WHERE meta_key LIKE '%blog_highlight_%' ORDER BY meta_key DESC;";

      return self::query( $query, false );
    }
  }
}
