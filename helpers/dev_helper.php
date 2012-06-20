<?php

namespace WpMvc
{
  class DevHelper
  {
    public static function dump( $value )
    {
      echo '<pre>';
      var_dump( $value );
      echo '</pre>';
    }

    public static function write( $value )
    {
      echo static::put_pre( $value );
    }

    private static function put_pre( $value )
    {
      $pre = '<pre>';
      $pre .= $value;
      $pre .= '</pre>';

      return $pre;
    }
  }
}