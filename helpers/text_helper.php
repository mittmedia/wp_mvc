<?php
namespace WpMvc
{
  class TextHelper
  {
    public static function shorten( $string, $limit, $break=" ", $pad="." )
    {
      $string = strip_tags ( $string );
      if(strlen($string) <= $limit) return $string;

      $string = substr($string, 0, $limit);

      if(($breakpoint = strrpos($string, $break)) !== false ) {
        $string = substr($string, 0, $breakpoint);
      }

      return $string . $pad;
    }
  }
}