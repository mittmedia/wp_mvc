<?php

namespace WpMvc
{
  class User extends \WpMvc\BaseModel
  {
    public static $table_name = 'wp_users';
    public static $class_name = '\WpMvc\User';
    public static $id_column = 'ID';
  }
}
