<?php

namespace WpMvc
{
  class ApplicationHelper
  {
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
