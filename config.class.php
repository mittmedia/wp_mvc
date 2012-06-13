<?php

namespace WpMvc
{
  class Config
  {
    public $home_path;
    public function home_path( $val = null )
    {
      if ( $val != null ) {
        $path = realpath( $val );

        if ( ! is_dir( $path ) )
          trigger_error( "You didn't specify a real path for your app.", E_USER_NOTICE );

        $this->home_path = $path;
      }

      return $this->home_path;
    }
  }
}
