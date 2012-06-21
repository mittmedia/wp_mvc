<?php

namespace WpMvc
{
  class Application
  {
    private $application_path;

    public function init( $path )
    {
      $this->application_path = $path;

      $this->init_controllers( $path . '/controllers' );
      $this->init_helpers( $path . '/helpers' );
      $this->init_models( $path . '/models' );
    }

    private function init_controllers( $path )
    {
      if ( is_dir( $path ) ) {
        $controller_iterator = $this->create_dir_iterator( $path );
        $this->iterate_dir_and_include( $controller_iterator );
        $this->iterate_dir_and_init( $controller_iterator );
      }
    }

    private function init_helpers( $path )
    {
      if ( is_dir( $path ) ) {
        $helper_iterator = $this->create_dir_iterator( $path );
        $this->iterate_dir_and_include( $helper_iterator );
      }
    }

    private function init_models( $path )
    {
      if ( is_dir( $path ) ) {
        $model_iterator = $this->create_dir_iterator( $path );
        $this->iterate_dir_and_include( $model_iterator );
      }
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

          $class_name = \WpMvc\ApplicationHelper::rename_controller_file_to_class( $controller_name );

          $this->{$controller_name} = new $class_name( $this->application_path );
        }
      }
    }
  }
}
