<?php
namespace WpMvc
{
  class TextHelper
  {
    public static function shorten( $string, $limit, $break=" ", $pad="..." )
    {
      if(strlen($string) <= $limit) return $string;

      $string = substr($string, 0, $limit);

      if(false !== ($breakpoint = strrpos($string, $break))) {
        $string = substr($string, 0, $breakpoint);
      }

      return $string . $pad;
    }
  }
}