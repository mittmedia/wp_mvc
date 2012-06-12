<?php

namespace WpMvc
{
  class Application
  {
    public function __constructor()
    {
      $this->config = new Config();
    }

    public function init_controllers()
    {
      echo "controllers";
    }

    public function init_models()
    {
      echo "models";
    }
  }
}
