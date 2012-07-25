<?php

namespace WpMvc
{
  class FormHelper
  {
    public static function render_form( $object, $content, $default_actions = true, $action = null, $verb = 'create' )
    {
      if ( $action == null )
        $action = $_SERVER['REQUEST_URI'];

      $html = '';

      if ($default_actions) {
        $html .= "<form action='$action' method='post' enctype='multipart/form-data'>";
      }

      $html .= "<table class='form-table'>";
      $html .= "<tbody>";

      foreach ( $content as $name => $value ) {
        $class_name = get_class( $object );
        $class_name_lowered = strtolower( $class_name );

        $title = null;
        $name = null;
        $type = null;
        $default_value = null;
        $description = null;
        $key = null;
        $options = null;

        isset( $value['title'] ) ? $title = $value['title'] : $title = null;

        isset( $value['name'] ) ? $name = $value['name'] : $name = null;

        isset( $value['type'] ) ? $type = $value['type'] : $type = null;

        isset( $value['default_value'] ) ? $default_value = $value['default_value'] : $default_value = null;

        isset( $value['description'] ) ? $description = $value['description'] : $description = null;

        isset( $value['key'] ) ? $key = $value['key'] : $key = null;

        isset( $value['options'] ) ? $options = $value['options'] : $options = null;

        if ( isset( $value['object'] ) ) {
          $sub_object = $value['object'];
          $sub_class_name = get_class( $sub_object );
          $sub_class_name_lowered = strtolower( $sub_class_name );
        }

        if ( $type == 'h3' ) {
          $html .= "</tbody></table><h3>$title</h3><tbody><table class='form-table'>";
        } else if ( $type == 'delete_action' ) {
          $delete_objects = $value['delete_objects'];

          $html_class_and_id = static::get_attribute_id( $delete_objects[0], array( $class_name_lowered, $sub_class_name_lowered ), $key );

          $delete_objects_attributes = "";

          foreach ( $delete_objects as $delete_objects_item ) {
            $delete_objects_attributes .= static::get_attribute_name( $delete_objects_item, array( $class_name_lowered, $sub_class_name_lowered ), $key ) . "[delete_action],";
          }

          $delete_objects = substr($delete_objects_attributes, 0, -1);

          $html .= "<tr class='action $html_class_and_id'><th><!-- --></th><td><input type='button' data-id='{$delete_objects_attributes}' class='delete_action' value='$title' /></td></tr>";
        } else if ( $type == 'spacer' ) {
          $html .= "<tr class='spacer'><th><!-- --></th><td><!-- --></td></tr>";
        } else if ( isset( $value['object'] ) ) {
          $html .= static::form_element( $title, $name, $type, array( $class_name_lowered, $sub_class_name_lowered ), array( $object, $sub_object ), $default_value, $description, $key, $options );
        } else {
          $html .= static::form_element( $title, $name, $type, $class_name_lowered, $object, null, null, null, $options );
        }
      }

      $html .= "</tbody>";
      $html .= "</table>";

      if ($default_actions) {
        $html .= static::default_actions( $verb );

        $html .= "</form>";
      }

      echo $html;
    }

    public static function form_element( $title, $name, $type, $class_name, $object, $default_value = null, $description, $key = null, $options = null )
    {
      $html_class = static::get_attribute_id( $name, $class_name );

      $html = "<tr valign='top' class='$html_class'>";

      if ( is_array( $options ) )
        $html .= "<th scope='row'>$title</th>";
      else
        $html .= "<th scope='row'><label for='" . static::get_attribute_id( $name, $class_name ) . "'>$title</label></th>";

      $html .= "<td>";

      switch ( $type ) {
        case 'text':
          $html .= static::input_text( $name, $class_name, $object, $default_value, $key );
          break;
        case 'editor_textarea':
          $html .= static::input_editor_textarea( $name, $class_name, $object, $default_value, $key );
          break;
        case 'textarea':
            $html .= static::input_textarea( $name, $class_name, $object, $default_value, $key );
          break;
        case 'select':
          $html .= static::input_select( $name, $class_name, $default_value, $key, $options );
          break;
        case 'checkboxes':
          $html .= static::input_checkboxes( $name, $class_name, $default_value, $key, $options );
          break;
        case 'file':
          $html .= static::input_file( $name, $class_name, $object, $default_value, $key );
          break;
      }

      if ( $description )
        $html .= "<br />$description";

      $html .= "</td>";
      $html .= "</tr>";

      return $html;
    }

    public static function input_text( $name, $class_name, $object, $default_value, $key )
    {
      if ( is_array( $class_name ) || is_array( $object ) ) {
        return "<input type='text' name='" . static::get_attribute_name( $name, $class_name, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='$default_value' />";
      } else {
        return "<input type='text' name='" . static::get_attribute_name( $name, $class_name ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='" . ( $default_value ? $default_value : $object->{$name} ) . "' />";
      }
    }


    public static function input_textarea( $name, $class_name, $object, $default_value, $key )
    {
      $html = "";

      if ( is_array( $class_name ) || is_array( $object ) ) {
        $html .= "<textarea name='" . static::get_attribute_name( $name, $class_name, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='large-text' rows='5' cols='30'>$default_value</textarea>";
      } else {
        $html .= "<textarea name='" . static::get_attribute_name( $name, $class_name ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='large-text' rows='5' cols='30'>" . ( $default_value ? $default_value : $object->{$name} ) . "</textarea>";
      }

      return $html;
    }

    public static function input_file( $name, $class_name, $object, $default_value, $key )
    {
      if ( is_array( $class_name ) || is_array( $object ) ) {
        return "<input type='file' name='" . static::get_attribute_name( $name, $class_name, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='$default_value' />";
      } else {
        return "<input type='file' name='" . static::get_attribute_name( $name, $class_name ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "' class='regular-text' value='" . ( $default_value ? $default_value : $object->{$name} ) . "' />";
      }
    }

    public static function input_editor_textarea( $name, $class_name, $object, $default_value, $key )
    {
      $html = "";
      ob_start();
      if ( is_array( $class_name ) || is_array( $object ) ) {
        $settings = array(
          'quicktags' => array(
            'buttons' => 'em,strong,link'
          ),
          'text_area_name' => static::get_attribute_name( $name, $class_name, $key ),
          'quicktags' => true,
          'tinymce' => true
        );
        wp_editor( $default_value, static::get_attribute_name( $name, $class_name, $key ), $settings );
      } else {
        $settings = array(
          'quicktags' => array(
            'buttons' => 'em,strong,link'
          ),
          'text_area_name' => static::get_attribute_name( $name, $class_name ),
          'quicktags' => true,
          'tinymce' => true
        );
        wp_editor( $default_value, static::get_attribute_name( $name, $class_name ), $settings );
      }

      $html .= ob_get_contents();
      ob_end_clean();

      return $html;
    }

    public static function input_select( $name, $class_name, $default_value, $key, $options )
    {
      $html = "<select name='" . static::get_attribute_name( $name, $class_name, $key ) . "' id='" . static::get_attribute_id( $name, $class_name ) . "'>";

      if ( static::is_associative_array( $options ) ) {
        foreach ( $options as $option_key => $option_value ) {
          $html .= "<option value='{$option_key}' " . ( $option_value == $default_value ? "selected='selected'" : "" ) . ">$option_value</option>";
        }
      } else {
        foreach ( $options as $option ) {
          $html .= "<option " . ( $option == $default_value ? "selected='selected'" : "" ) . ">$option</option>";
        }
      }

      $html .= "</select>";

      return $html;
    }

    public static function input_checkboxes( $name, $class_name, $default_value, $key, $options )
    {
      $html = "";

      foreach ( $options as $option_key => $option_value ) {
        $html .= "<label for='" . static::get_attribute_id( $name, $class_name, $option_key ) . "'>";
        $html .= "<input name='" . static::get_attribute_name( $name, $class_name, $option_key ) . "' type='checkbox' id='" . static::get_attribute_id( $name, $class_name, $option_key ) . "' " . ( $option_value['default_value'] ? "checked='checked'" : "" ) . " />";
        $html .= " {$option_value['title']}";
        $html .= "</label><br />";
      }

      return $html;
    }

    public static function default_actions($verb)
    {
      switch ($verb) {
        case 'create':
          return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Save Changes' ) . "'></p>";
        case 'upload':
          return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Upload' ) . "'></p>";
        case 'yes':
          return "<p class='submit'><input type='submit' name='submit' id='submit' class='button-primary' value='" . __( 'Yes' ) . "'></p>";
      }
    }

    private static function get_attribute_name( $name, $class_name, $key = null )
    {
      if ( is_array( $class_name ) ) {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name[0]}[{$class_name[1]}][{$name}]" . ( $key ? "[{$key}]" : '' );
      } else {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name}[{$name}]" . ( $key ? "[{$key}]" : '' );
      }
    }

    private static function get_attribute_id( $name, $class_name, $key = null )
    {
      if ( is_array( $class_name ) ) {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name[0]}_{$class_name[1]}_{$name}" . ( $key ? "_{$key}" : '' );
      } else {
        \WpMvc\ApplicationHelper::remove_namespace_with_split( &$class_name );

        return "{$class_name}_{$name}" . ( $key ? "_{$key}" : '' );
      }
    }

    private static function is_associative_array( $arr )
    {
      # Function found at http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-numeric

      if ( ! is_array( $arr ) )
        return false;

      return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
    }
  }
}
