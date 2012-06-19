<?php

namespace WpMvc
{
  class FormHelper
  {
    public static function render_form( $object, $content, $action = null, $verb = 'create' )
    {
      if ( $action == null )
        $action = $_SERVER['REQUEST_URI'];

      $class_name = get_class( $object );
      $class_name_lowered = strtolower( $class_name );

      $html = "<form action='$action' method='post'>";

      foreach ( $content as $name => $type_or_value ) {
        if ( $name == 'object' ) {
          $sub_object = $type_or_value['object'];
          $sub_class_name = get_class( $sub_object );
          $sub_class_name_lowered = strtolower( $sub_class_name );

          $html .= static::form_element( $type_or_value['name'], $type_or_value['type'], array( $class_name_lowered, $sub_class_name_lowered ), array( $object, $sub_object ), $type_or_value['default_value'], $type_or_value['key'] );
        } else {
          $html .= static::form_element( $name, $type_or_value, $class_name_lowered, $object );
        }
      }

      $html .= static::default_actions( $verb );

      $html .= "</form>";

      echo $html;
    }

    public static function form_element( $name, $type, $class_name, $object, $default_value = null, $key = null )
    {
      $html = "";

      switch ( $type ) {
        case 'text':
          $html = static::input_text( $name, $class_name, $object, $default_value, $key );
          break;
        case 'textarea':
          $html = $this->input_textarea( $name );
          break;
      }

      return $html;
    }

    public static function input_text( $name, $class_name, $object, $default_value, $key )
    {
      if ( is_array( $class_name ) || is_array( $object )  )
        return "<input type='text' name='{$class_name[0]}[{$class_name[1]}][{$name}]" . ( $key ? "[{$key}]" : '' ) . "' id='{$class_name[0]}_{$class_name[1]}_{$name}' class='standard-text' value='$default_value' />";
      else
        return "<input type='text' name='{$class_name}[{$name}]' id='{$class_name}_{$name}' class='standard-text' value='" . ( $default_value ? $default_value : $object->{$name} ) . "' />";
    }

    public static function input_textarea( $name )
    {
      $class_name = $this->class_name;
      $object_value = $this->object->{$name};

      return "<textarea name='{$class_name}[{$name}]' id='{$class_name}_{$name}' class='standard-text'>$object_value</textarea>";
    }

    public static function default_actions( $verb )
    {
      switch ( $verb ) {
        case 'create':
          return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Create' ) . "'></p>";
          break;
        case 'update':
          return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Update' ) . "'></p>";
          break;
      }
    }
  }
}
