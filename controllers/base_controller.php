<?php

namespace WpMvc
{
  class BaseController
  {
    public static function render( $controller, $action )
    {
      $controller_class = get_class( $controller );

      $controller_name = \WpMvc\Application::rename_controller_class_to_file( $controller_class );
      $controller_dir = preg_replace( '/_controller/', '', $controller_name );
      $action_file = $action . '.php';

      $full_path = \WpMvc\Config::$home_path . '/views/' . $controller_dir . '/' . $action_file;

      include( $full_path );
    }
  }
}
