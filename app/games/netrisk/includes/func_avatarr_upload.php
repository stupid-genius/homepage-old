<?php
function get_content($url)
{
   $ch = curl_init();

   curl_setopt ($ch, CURLOPT_URL, $url);
   curl_setopt ($ch, CURLOPT_HEADER, 0);

   ob_start();

   curl_exec ($ch);
   curl_close ($ch);
   $string = ob_get_contents();

   ob_end_clean();
  
   return $string;   
}


    function avatarr_url($file_url) {
      $size = array();
      $maxsize = 150000;
      $resize_image = false;

      $imgData = get_content($file_url);

      $srcimg = imagecreatefromstring($imgData);

      $size[0] = imagesx($srcimg);
      $size[1] = imagesy($srcimg);
      
        //Is width less than or equal to 40 px?
        //Is height less than or equal to 40 px?
        if(($size[0] >= 40) || ($size[1] >= 40))
              $resize_image = true;
        else
              $resize_image = false;

        //if either is larger, take the longest sided dimension and find the new dimension
        if($resize_image) {
            if($size[0] > $size[1]) {
               //find scaling multiplier
               $scale_ratio = $size[0]/40;
            }
            else {
               $scale_ratio = $size[1]/40;
            }

            $new_size_x = $size[0]/$scale_ratio;
            $new_size_y = $size[1]/$scale_ratio;

            $destimg = imagecreatetruecolor($new_size_x, $new_size_y);

            imagecopyresampled($destimg, $srcimg, 0, 0, 0, 0, $new_size_x, $new_size_y, $size[0], $size[1]);
            ob_start();
            imagejpeg($destimg);
            imagedestroy($destimg);
            $imgData = addslashes(ob_get_contents());
            ob_end_clean();
        }

        $sql = "UPDATE users SET image_type='image/jpeg', avatar='".$imgData."', 
                image_name='".$file_url."'
                WHERE login='".$_SESSION['player_name']."'";

        // insert the image
        if(!mysql_query($sql)) {
            echo 'Unable to upload file';
            }


  }
      


    // the upload function
    function avatarr_upload(){
    $maxsize = 150000;
    $resize_image = false;

    if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {

        // check the file is less than the maximum file size
        if($_FILES['userfile']['size'] < $maxsize)
            {
        // prepare the image for insertion
        $imgData_raw = file_get_contents($_FILES['userfile']['tmp_name']);
        $imgData =addslashes($imgData_raw);

        //$imgData =addslashes (file_get_contents($_FILES['userfile']['tmp_name']));

        // get the image info..
        $size = getimagesize($_FILES['userfile']['tmp_name']);

        //Is width less than or equal to 40 px?
        //Is height less than or equal to 40 px?
        if(($size[0] >= 40) || ($size[1] >= 40))
              $resize_image = true;
        else
              $resize_image = false;

        //if either is larger, take the longest sided dimension and find the new dimension
        if($resize_image) {
            if($size[0] > $size[1]) {
               //find scaling multiplier
               $scale_ratio = $size[0]/40;
            }
            else {
               $scale_ratio = $size[1]/40;
            }

            $new_size_x = $size[0]/$scale_ratio;
            $new_size_y = $size[1]/$scale_ratio;

            $destimg = imagecreatetruecolor($new_size_x, $new_size_y);
            $srcimg = imagecreatefromstring($imgData_raw);

            imagecopyresampled($destimg, $srcimg, 0, 0, 0, 0, $new_size_x, $new_size_y, $size[0], $size[1]);
            ob_start();
            switch($size['mime']) {
               case 'image/gif': imagegif($destimg);
                                 break;
               case 'image/jpeg':imagejpeg($destimg);
                                 break;
               case 'image/png': imagepng($destimg);
                                 break;
               case 'image/bmp': imagejpeg($destimg);
                                 break;
            }
            imagedestroy($destimg);
            $imgData = addslashes(ob_get_contents());
            ob_end_clean();
        }



        



        // our sql query
//                image_size_x='".$size[0]."',
//                image_size_y='".$size[1]."',

        $sql = "UPDATE users SET image_type='".$size['mime']."', avatar='".$imgData."', 
                image_name='".$_FILES['userfile']['name']."'
                WHERE login='".$_SESSION['player_name']."'";

        // insert the image
        if(!mysql_query($sql)) {
            echo 'Unable to upload file';
            }


        }

    }
    else {
         //Do nothing
         $dummy = true;
         }
    }
?>