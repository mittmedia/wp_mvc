<?php

namespace WpMvc
{
  class Application
  {
    public function init()
    {
      $home_path = \WpMvc\Config::$home_path;

      $this->init_controllers( $home_path . '/controllers' );
      $this->init_helpers( $home_path . '/helpers' );
      $this->init_models( $home_path . '/models' );
    }

    private function init_controllers( $path )
    {
      $controller_iterator = $this->create_dir_iterator( $path );
      $this->iterate_dir_and_include( $controller_iterator );
      $this->iterate_dir_and_init( $controller_iterator );
    }

    private function init_helpers( $path )
    {
      $helper_iterator = $this->create_dir_iterator( $path );
      $this->iterate_dir_and_include( $helper_iterator );
    }

    private function init_models( $path )
    {
      $model_iterator = $this->create_dir_iterator( $path );
      $this->iterate_dir_and_include( $model_iterator );
    }

    private function create_dir_iterator( $path )
    {
      return new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $path ), 
        \RecursiveIteratorIterator::CHILD_FIRST );
    }

    private function iterate_dir_and_include( $iterator )
    {
      foreach ($iterator as $path) {
        if ( ! $path->isDir() ) {
          include_once( $path );
        }
      }
    }

    private function iterate_dir_and_init( $iterator )
    {
      foreach ($iterator as $path) {
        if ( ! $path->isDir() ) {
          $controller_file_name = basename( $path );
          $controller_name = preg_replace( '/\.php/', '', $controller_file_name );

          $class_name = static::rename_controller_file_to_class( $controller_name );

          $this->{$controller_name} = new $class_name();
        }
      }
    }

    public static function rename_controller_file_to_class( $controller_name )
    {
      $class_name_splitted = explode( '_', $controller_name );

      $class_name = '';

      foreach ( $class_name_splitted as $class_name_part ) {
        $class_name .= ucfirst( $class_name_part );
      }

      return $class_name;
    }

    public static function rename_controller_class_to_file( $class_name )
    {
      $class_name_with_spaces = preg_replace( '/([a-z0-9])?([A-Z])/', '$1 $2', $class_name);

      $class_name_splitted = explode( ' ', $class_name_with_spaces );

      $controller_name = '';

      foreach ( $class_name_splitted as $class_name_part ) {
        $controller_name .= '_' . strtolower( $class_name_part );
      }

      return substr( $controller_name, 2 );
    }
  }
}
