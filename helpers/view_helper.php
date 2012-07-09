<?php

namespace WpMvc
{
  class ViewHelper
  {
    public static function render_partial( $path )
    {
      $partial_path_splitted = explode( '/', $path );
      $partial_name = '_' . array_pop( $partial_path_splitted ) . '.html.php';
      $partial_path = \WpMvc\Config::$application_path;
      $partial_path .= '/views/';
      $partial_path .= implode( '/', $partial_path_splitted );
      $partial_path .= '/';
      $partial_path .= $partial_name;

      include( $partial_path );
    }

    public static function render_template( $path, $template_object )
    {
      $template_path_splitted = explode( '/', $path );
      $template_name = array_pop( $template_path_splitted ) . '.php';
      $template_path = \WpMvc\Config::$application_path;
      $template_path .= '/views/';
      $template_path .= implode( '/', $template_path_splitted );
      $template_path .= '/';
      $template_path .= $template_name;

      include( $template_path );
    }

    public static function admin_notice( $message )
    {
      $html = <<<html

<div class="updated">
  <p>$message</p>
</div>

html;

      return $html;
    }

    public static function admin_error( $message )
    {
      $html = <<<html

<div class="error">
  <p>$message</p>
</div>

html;

      return $html;
    }
  }
}
