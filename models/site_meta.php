<?php

class SiteMeta extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_sitemeta';
  public static $class_name = 'SiteMeta';
  public static $id_column = 'meta_id';

  public static function find_by_site_id( $site_id )
  {
    global $wpdb;

    $table = static::$table_name;

    $query = "SELECT * FROM $table WHERE site_id = $site_id;";

    return self::query( $query );
  }
}
