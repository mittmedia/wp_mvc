<?php

namespace WpMvc
{
  class Config
  {
    public static $home_path;
    public static function home_path( $val = null )
    {
      if ( $val != null ) {
        $path = realpath( $val );

        if ( ! is_dir( $path ) )
          trigger_error( "You didn't specify a real path for your app.", E_USER_ERROR );

        static::$home_path = $path;
      }

      return static::$home_path;
    }
  }
}
