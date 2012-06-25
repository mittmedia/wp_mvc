<?php

namespace WpMvc
{
  class BaseController
  {
    private $application_path;

    public function __construct( $path )
    {
      $this->application_path = $path;
    }

    public function render( $controller, $action )
    {
      $controller_class = get_class( $controller );

      $controller_name = \WpMvc\ApplicationHelper::rename_controller_class_to_file_without_namespace( $controller_class );
      $controller_dir = preg_replace( '/_controller/', '', $controller_name );
      $action_file = $action . '.php';

      $full_path = $this->application_path . '/views/' . $controller_dir . '/' . $action_file;

      include( $full_path );
    }

    public static function redirect_to( $url )
    {
      echo "<meta http-equiv='refresh' content='0; URL=$url' />";
    }
  }
}
