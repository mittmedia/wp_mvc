<?php

class Site extends \WpMvc\BaseModel
{
  public static $table_name = 'wp_site';
  public static $class_name = 'Site';
  public static $id_column = 'id';
  public $sitemeta;

  public function init()
  {
    static::has_many_meta();
  }

  private function has_many_meta()
  {
    $meta = \SiteMeta::find_by_site_id( $this->{static::$id_column} );

    foreach ( $meta as $meta_item ) {
      $this->sitemeta->{$meta_item->meta_key} = $meta_item;
    }
  }
}