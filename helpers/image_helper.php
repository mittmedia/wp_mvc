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

      list( $imagewidth, $imageheight, $imagetype ) = getimagesize( $image );
      $imagetype = image_type_to_mime_type( $imagetype );

      if ( ( $imageheight > $fixed_height ) && ( $imagetype == "image/x-png" || $imagetype == "image/png" ) ) {
        $scale =  $fixed_height / $imageheight;
        $new_image = imagecreatetruecolor( floor( $imagewidth * $scale ), $fixed_height);
        imagealphablending( $new_image, false );
        imagesavealpha( $new_image, true );
        $source = imagecreatefrompng( $image );
        imagecopyresampled( $new_image, $source, 0, 0, 0, 0, $imagewidth * $scale, $fixed_height, $imagewidth, $imageheight );

        if ( $options["save_image"] == true ) {
          imagepng( $new_image, $image_path . $new_filename );
          chmod($image_path . $new_filename, 0777);
        } else {
          return $new_image;
        }
      }

      return true;
    }

    public static function width_of($path)
    {
      $size = getimagesize($path);
      $width = $size[0];
      return $width;
    }

    public static function height_of($path)
    {
      $size = getimagesize($path);
      $height = $size[1];
      return $height;
    }

    public static function resize($path, $width, $height, $scale)
    {
      list($image_width, $image_height, $image_type) = getimagesize($path);
      $image_type = image_type_to_mime_type($image_type);
      $new_image_width = ceil($width * $scale);
      $new_image_height = ceil($height * $scale);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      switch ($image_type) {
        case 'image/gif':
          $source = imagecreatefromgif($path);
          break;
        case 'image/pjpeg':
        case 'image/jpeg':
        case 'image/jpg':
          $source = imagecreatefromjpeg($path);
          break;
        case 'image/png':
        case 'image/x-png':
          $source = imagecreatefrompng($path);
          break;
        }

      imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_image_width, $new_image_height, $width, $height);

      switch ($image_type) {
        case 'image/gif':
            imagegif($new_image, $path);
          break;
          case 'image/pjpeg':
        case 'image/jpeg':
        case 'image/jpg':
            imagejpeg($new_image, $path, 90);
          break;
        case 'image/png':
        case 'image/x-png':
          imagepng($new_image, $path);
          break;
      }

      chmod($path, 0777);
    }

    public static function crop($path, $full_image_path, $x1, $y1, $width, $height, $scale)
    {
      list($image_width, $image_height, $image_type) = getimagesize($full_image_path);
      $image_type = image_type_to_mime_type($image_type);
      $new_image_width = ceil($width * $scale);
      $new_image_height = ceil($height * $scale);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      switch ($image_type) {
        case 'image/gif':
          $source = imagecreatefromgif($full_image_path);
          break;
        case 'image/pjpeg':
        case 'image/jpeg':
        case 'image/jpg':
          $source = imagecreatefromjpeg($full_image_path);
          break;
        case 'image/png':
        case 'image/x-png':
          $source = imagecreatefrompng($full_image_path);
          break;
        }

      imagecopyresampled($new_image, $source, 0, 0, $x1, $y1, $new_image_width, $new_image_height, $width, $height);

      switch ($image_type) {
        case 'image/gif':
            imagegif($new_image, $path);
          break;
          case 'image/pjpeg':
        case 'image/jpeg':
        case 'image/jpg':
            imagejpeg($new_image, $path, 90);
          break;
        case 'image/png':
        case 'image/x-png':
          imagepng($new_image, $path);
          break;
      }

      chmod($path, 0777);
    }
  }
}