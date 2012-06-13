<?php

namespace WpMvc
{
  class FormHelper
  {
    protected static $object;
    protected static $class_name;

    public static function render_form( $object, $content, $action = null )
    {
      if ( $action == null )
        $action = $_SERVER['REQUEST_URI'];

      static::$object = $object;

      $class_name = get_class( $object );
      static::$class_name = strtolower( $class_name );

      $html = "<form action='$action' method='post'>";

      foreach ( $content as $name => $type ) {
        $html .= static::form_element( $name, $type );
      }

      $html .= static::default_actions();

      $html .= "</form>";

      echo $html;
    }

    public static function form_element( $name, $type )
    {
      $html = "";

      switch ( $type ) {
        case 'text':
          $html = static::input_text( $name );
          break;
        case 'textarea':
          $html = static::input_textarea( $name );
          break;
      }

      return $html;
    }

    public static function input_text( $name )
    {
      $class_name = static::$class_name;
      $object_value = static::$object->attr( $name );

      return "<input type='text' name='{$class_name}[{$name}]' id='{$class_name}_{$name}' class='standard-text' value='$object_value' />";
    }

    public static function input_textarea( $name )
    {
      $class_name = static::$class_name;
      $object_value = static::$object->attr( $name );

      return "<textarea name='{$class_name}[{$name}]' id='{$class_name}_{$name}' class='standard-text'>$object_value</textarea>";
    }

    public static function default_actions()
    {
      return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='Update'></p>";
    }
  }
}
