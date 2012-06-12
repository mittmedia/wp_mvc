<?php

namespace WpMvc
{
  class Config
  {
    private $plugin_dir;
    public function set_plugin_dir( $val ) { $this->plugin_dir = $val; }
    public function get_plugin_dir() { return $this->plugin_dir; }

    private $plugin_name;
    public function set_plugin_name( $val ) { $this->plugin_name = $val; }
    public function get_plugin_name() { return $this->plugin_name; }
  }
}
