<?php

namespace WpMvc
{
  class ImageHelper
  {
    public static function resample_png( $options )
    {
      $image_path = $options["path"];
      $file = $options["sample_filename"];
      $new_filename = $options["new_filename"];
      $fixed_height = $options["new_height"];

      $image = $image_path . $file;

      list($imagewidth, $imageheight, $imagetype) = getimagesize($image);
      $imagetype = image_type_to_mime_type($imagetype);

      if(($imageheight > $fixed_height) && ($imagetype == "image/x-png" || $imagetype == "image/png")) {
        $scale =  $fixed_height / $imageheight;
        $new_image = imagecreatetruecolor(floor($imagewidth * $scale), $fixed_height);
        $source = imagecreatefrompng($image);
        imagecopyresampled($new_image, $source, 0, 0, 0, 0, $imagewidth * $scale, $fixed_height, $imagewidth, $imageheight);

        if ( $options["save_image"] == true) {
          imagepng($new_image, $image_path . $new_filename);
          chmod($image_path . $new_filename, 0777);
        } else {
          return $new_image;
        }
      }
      return true;
    }
  }
}