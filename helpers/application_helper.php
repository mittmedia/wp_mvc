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

    public static function rename_controller_file_to_namespace_and_class( $namespace, $controller_name )
    {
      $class_name_splitted = explode( '_', $controller_name );

      $class_name = '';

      foreach ( $class_name_splitted as $class_name_part ) {
        $class_name .= ucfirst( $class_name_part );
      }

      return "{$namespace}{$class_name}";
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

    public static function rename_controller_class_to_file_without_namespace( $class_name )
    {
      $class_name_with_spaces = preg_replace( '/([a-z0-9])?([A-Z])/', '$1 $2', $class_name);

      $class_name_splitted = explode( ' ', $class_name_with_spaces );

      $controller_name = '';

      foreach ( $class_name_splitted as $class_name_part ) {
        $controller_name .= '_' . strtolower( $class_name_part );
      }

      $controller_name_array = explode( '\\', $controller_name );

      return substr( $controller_name_array[1], 1 );
    }

    public static function remove_namespace_with_split( $class_name )
    {
      if ( is_array( $class_name ) ) {
        for ( $i = 0; $i < count( $class_name ); $i++ ) {
          $class_name_array = explode( '\\', $class_name[$i] );

          $class_name[$i] = $class_name_array[1];
        }
      } else {
        $class_name_array = explode( '\\', $class_name );

        $class_name = $class_name_array[1];
      }
    }

    public static function websafe_name( $input )
    {
      # Found below code at http://stackoverflow.com/questions/4783802/converting-string-into-web-safe-uri

      $replace = array();
      $delimiter = '';

      $input = preg_replace( array( '/å/', '/ä/', '/ö/', '/Å/', '/Ä/', '/Ö/'), array( 'a', 'a', 'o', 'A', 'A', 'O'), $input );

      if ( ! empty( $replace ) ) {
        $input = str_replace( (array)$replace, ' ', $input );
      }

      $clean = iconv( 'UTF-8', 'ASCII//TRANSLIT', $input );
      $clean = preg_replace( '/[^a-zA-Z0-9\/_|+ -]/', '', $clean );
      $clean = strtolower( trim( $clean, '-' ) );
      $clean = preg_replace( '/[\/_|+ -]+/', $delimiter, $clean );

      return $clean;
    }

    public static function unique_identifier( $input )
    {
      $websafe_name = static::websafe_name( $input );

      # Found below code at http://php.about.com/od/security/p/unique_id.htm

      $c = uniqid( rand(), true );

      $hash = md5( $websafe_name . $c );

      return $hash;
    }

    public static function current_blogname( $prepend = "", $append = "" )
    {
      $wpurl = explode('/', get_bloginfo('wpurl'));
      $blog_name = "";
      if (count($wpurl) > 3)
        $blog_name = $prepend . $wpurl[3] . $append;
      return $blog_name;
    }
  }
}
