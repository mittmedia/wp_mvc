<?php

namespace WpMvc
{
  class Application
  {
    public $config;

    public function __construct()
    {
      $this->config = new \WpMvc\Config();
    }

    public function init()
    {
      
    }
  }
}
