<?php

namespace WpMvc
{
  class ViewHelper
  {
    public static function render_partial( $path )
    {
      $partial_path_splitted = explode( '/', $path );
      $partial_name = '_' . array_pop( $partial_path_splitted ) . '.php';
      $partial_path = \WpMvc\Config::$home_path;
      $partial_path .= '/views/';
      $partial_path .= implode( '/', $partial_path_splitted );
      $partial_path .= '/';
      $partial_path .= $partial_name;

      include( $partial_path );
    }

    public static function render_template( $path )
    {
      $template_path_splitted = explode( '/', $path );
      $template_name = array_pop( $template_path_splitted ) . '.php';
      $template_path = \WpMvc\Config::$home_path;
      $template_path .= '/views/';
      $template_path .= implode( '/', $template_path_splitted );
      $template_path .= '/';
      $template_path .= $template_name;

      include( $template_path );
    }
  }
}
