<?php

namespace WpMvc
{
  class FormHelper
  {
    public static function render_form( $object, $content, $action = null, $verb = 'create' )
    {
      if ( $action == null )
        $action = $_SERVER['REQUEST_URI'];

      $html = "<form action='$action' method='post'>";
      $html .= "<table class='form-table'>";
      $html .= "<tbody>";

      foreach ( $content as $name => $value ) {
        $class_name = get_class( $object );
        $class_name_lowered = strtolower( $class_name );

        if ( isset( $value['object'] ) ) {
          $sub_object = $value['object'];
          $sub_class_name = get_class( $sub_object );
          $sub_class_name_lowered = strtolower( $sub_class_name );

          $options = null;

          if ( isset( $value['options'] ) )
            $options = $value['options'];

          $html .= static::form_element( $value['title'], $value['name'], $value['type'], array( $class_name_lowered, $sub_class_name_lowered ), array( $object, $sub_object ), $value['default_value'], $value['key'], $options );
        } else {
          $html .= static::form_element( $value['title'], $value['name'], $value['type'], $class_name_lowered, $object );
        }
      }

      $html .= "</tbody>";
      $html .= "</table>";

      $html .= static::default_actions( $verb );

      $html .= "</form>";

      echo $html;
    }

    public static function form_element( $title, $name, $type, $class_name, $object, $default_value = null, $key = null, $options = null )
    {
      $html = "<tr valign='top'>";
      $html .= "<th scope='row'><label for='" . static::get_attribute_id( $name, $class_name ) . "'>" . __( $title ) . "</label></th>";
      $html .= "<td>";

      switch ( $type ) {
        case 'text':
          $html .= static::input_text( $name, $class_name, $object, $default_value, $key );
          break;
        case 'select':
          $html .= static::input_select( $name, $class_name, $object, $default_value, $key, $options );
          break;
      }

      $html .= "</td>";
      $html .= "</tr>";

      return $html;
    }

    public static function input_text( $name, $class_name, $object, $default_value, $key )
    {
      if ( is_array( $class_name ) || is_array( $object ) ) {
        return "<input type='text' name='" . static::get_attribute_name( $name, $class_name, $object, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='$default_value' />";
      } else {
        return "<input type='text' name='" . static::get_attribute_name( $name, $class_name ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='" . ( $default_value ? $default_value : $object->{$name} ) . "' />";
      }
    }

    public static function input_select( $name, $class_name, $object, $default_value, $key, $options )
    {
      $html = "<select name='" . static::get_attribute_name( $name, $class_name, $object, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "'>";

      foreach ( $options as $option ) {
        if ( $option == $default_value )
          $html .= "<option selected='selected'>$option</option>";
        else
          $html .= "<option>$option</option>";
      }

      $html .= "</select>";

      return $html;
    }

    public static function default_actions()
    {
      return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Save Changes' ) . "'></p>";
    }

    private static function get_attribute_name( $name, $class_name, $object = null, $key = null )
    {
      if ( is_array( $class_name ) || is_array( $object ) ) {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name[0]}[{$class_name[1]}][{$name}]" . ( $key ? "[{$key}]" : '' );
      } else {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name}[{$name}]";
      }
    }

    private static function get_attribute_id( $name, $class_name )
    {
      if ( is_array( $class_name ) ) {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name[0]}_{$class_name[1]}_{$name}";
      } else {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name}_{$name}";
      }
    }
  }
}
