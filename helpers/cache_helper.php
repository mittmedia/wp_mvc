<?php

namespace WpMvc
{
  class CacheHelper
  {
    public static function start_recording_cache($name)
    {
      $cachefile_tmp = WP_CONTENT_DIR . "/uploads/wpmvc/cache/" . $name . "_tmp.html";

      if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == "true" && file_exists($cachefile_tmp)) {
        unlink($cachefile_tmp);
      }

      if (!is_dir(WP_CONTENT_DIR . "/uploads")) {
        mkdir(WP_CONTENT_DIR . "/uploads/", 0777);
        chmod(WP_CONTENT_DIR . "/uploads/", 0777);
      }

      if (!is_dir(WP_CONTENT_DIR . "/uploads/wpmvc")) {
        mkdir(WP_CONTENT_DIR . "/uploads/wpmvc/", 0777);
        chmod(WP_CONTENT_DIR . "/uploads/wpmvc/", 0777);
      }

      if (!is_dir(WP_CONTENT_DIR . "/uploads/wpmvc/cache")) {
        mkdir(WP_CONTENT_DIR . "/uploads/wpmvc/cache/", 0777);
        chmod(WP_CONTENT_DIR . "/uploads/wpmvc/cache/", 0777);
      }
      
      ob_start(); // start the output buffer
    }

    public static function stop_recording_cache($name)
    {
      $cachefile_tmp = WP_CONTENT_DIR . "/uploads/wpmvc/cache/" . $name . "_tmp.html";
      $cachefile     = WP_CONTENT_DIR . "/uploads/wpmvc/cache/" . $name . ".html";

      $fp = fopen($cachefile_tmp, 'w');
      fwrite($fp, ob_get_contents());
      fclose($fp);
      chmod($cachefile_tmp, 0777);

      copy($cachefile_tmp, $cachefile);

      ob_end_flush();
    }

    public static function serve_cache($name)
    {
      $cachefile = WP_CONTENT_DIR . "/uploads/wpmvc/cache/" . $name . ".html";
      $cachetime = 10 * 60; // 10 minutes
      // Serve from the cache if it is younger than $cachetime
      if (file_exists($cachefile))
      {
        include($cachefile);
        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
        exit;
      }
    }
  }
}

// namespace WpMvc
// {
//   class ViewHelper
//   {
//     public static function render_partial( $path )
//     {
//       $partial_path_splitted = explode( '/', $path );
//       $partial_name  = '_' . array_pop( $partial_path_splitted ) . '.html.php';
//       $partial_path  = implode( '/', $partial_path_splitted );
//       $partial_path .= '/';
//       $partial_path .= $partial_name;

//       include( $partial_path );
//     }

//     public static function render_template( $path, $template_object )
//     {
//       $template_path_splitted = explode( '/', $path );
//       $template_name = array_pop( $template_path_splitted ) . '.html.php';
//       $template_path = \WpMvc\Config::$application_path;
//       $template_path .= '/views/';
//       $template_path .= implode( '/', $template_path_splitted );
//       $template_path .= '/';
//       $template_path .= $template_name;

//       include( $template_path );
//     }

//     public static function admin_notice( $message )
//     {
//       $html = <<<html

// <div class="updated">
//   <p>$message</p>
// </div>

// html;

//       return $html;
//     }

//     public static function admin_error( $message )
//     {
//       $html = <<<html

// <div class="error">
//   <p>$message</p>
// </div>

// html;

//       return $html;
//     }
//   }
// }


// <?php
//   namespace WST;

//   die(get_template_directory() . "");

//   class StartpageController extends \WpMvc\BaseController
//   {
//     $cachefile = WP_CONTENT_DIR . "/uploads/portal/cache/startpage_index.html";

//     if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == "true" && file_exists($cachefile)) {
//       unlink($cachefile);
//     }

//     if (!is_dir(WP_CONTENT_DIR . "/uploads/portal")) {
//       mkdir(WP_CONTENT_DIR . "/uploads/portal/", 0777);
//       chmod(WP_CONTENT_DIR . "/uploads/portal/", 0777);
//     }

//     if (!is_dir(WP_CONTENT_DIR . "/uploads/portal/cache")) {
//       mkdir(WP_CONTENT_DIR . "/uploads/portal/cache/", 0777);
//       chmod(WP_CONTENT_DIR . "/uploads/portal/cache/", 0777);
//     }
//     $cachetime = 30 * 60; // 30 minutes
//     // Serve from the cache if it is younger than $cachetime
//     if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
//     {
//       include($cachefile);
//       echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
//       exit;
//     }
//     ob_start(); // start the output buffer
//   }

//   // open the cache file for writing
//     $fp = fopen($cachefile, 'w');
//     // save the contents of output buffer to the file
//     fwrite($fp, ob_get_contents());
//     // close the file
//     fclose($fp);
//     chmod($cachefile, 0777);
//     // Send the output to the browser
//     ob_end_flush();