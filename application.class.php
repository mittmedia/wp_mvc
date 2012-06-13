<?php

namespace WpMvc
{
  class Application
  {
    public $config;

    public function __construct()
    {
      $this->config = new \WpMvc\Config();
    }

    public function init()
    {
      $home_path = $this->config->home_path;

      $this->init_controllers( $home_path . "/controllers" );
      $this->init_models( $home_path . "/models" );
    }

    public function controller(  )
    {
      $this->config = new \WpMvc\Controller();
    }

    private function init_controllers( $path )
    {
      $controller_iterator = $this->create_dir_iterator( $path );
      $this->iterate_dir_and_include( $controller_iterator );
      $this->iterate_dir_and_init( $controller_iterator );
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
          $controller_name = preg_replace( "/\.php/", "", $controller_file_name );

          $class_name = $this->rename_controller_file_to_class( $controller_name );

          $this->{$controller_name} = new $class_name();
        }
      }
    }

    private function rename_controller_file_to_class( $controller_name )
    {
      $class_name_splitted = explode( "_", $controller_name );

      $class_name = "";

      foreach ( $class_name_splitted as $class_name_part ) {
        $class_name .= ucfirst( $class_name_part );
      }

      //$class_name = preg_replace( "/_?/e", "$1", $controller_name );
      //$class_name = ucfirst( $class_name );

      return $class_name;
    }
  }
}
