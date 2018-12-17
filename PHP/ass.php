<?php
    while($row = mysqli_fetch_array($query)) {
      
        $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
        $SetID = $row['setID'];
        
        $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID);
        
        $imageinfo = mysqli_fetch_array($imagesearch);
        if($imageinfo['has_jpg']) { 
          $filename = "S/$SetID.jpg";
        } 
        else if($imageinfo['has_gif']) { 
          $filename = "S/$SetID.gif";
        } 
        else { 
          $filename = "noimage_small.png";
        }
        
        
        print("<img src=\"$prefix$filename\" />");
      }
    ?>


      
      $setArr = array();
      
      

      while($res = mysqli_fetch_array($query)) {
        $setArr[] = $res;
      }
      
      //$contents = mysqli_query($connection, )