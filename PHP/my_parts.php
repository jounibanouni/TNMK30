<?php 
    include 'menu.txt'; 
    $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
    if (!$connection) {
      die('MySQL connection error');
    }
     
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
  ?>
  <main>
  <?php
    $query = mysqli_query($connection, "SELECT categories.CatID, categories.Categoryname, parts.Partname, parts.PartID, colors.ColorID, colors.Colorname, inventory.Quantity FROM categories, sets, collection, parts, colors, inventory 
    WHERE sets.SetID=collection.SetID AND sets.SetID=inventory.SetID AND sets.CatID=categories.CatID AND inventory.ItemID=parts.PartID AND inventory.ColorID=colors.ColorID ORDER BY PartID LIMIT 10");
    print("<table>\n<tr>");
    print("<th>Picture</th><th>Quantity</th><th>Part Name</th> <th>Color</th><th>Category</th> <th>Part ID</th> </tr>\n");
    while($row = mysqli_fetch_array($query)) {
      $quantity = $row['Quantity'];
      $partName = $row['Partname'];
      $partID = $row['PartID'];
      $colorName = $row['Colorname'];
      $colorID = $row['ColorID'];
      $category = $row['Categoryname'];
      
      /*$prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
        
      $imagesearch = mysqli_query($connection, "SELECT * FROM images, parts WHERE ItemTypeID='P' AND images.ItemID=parts.PartID ORDER BY ItemID LIMIT 10");
      
      $imageinfo = mysqli_fetch_array($imagesearch);
      if($imageinfo['has_jpg']) { 
        $filename = "P/$colorID/$partID.jpg";
      } 
      else if($imageinfo['has_gif']) { 
        $filename = "P/$colorID/$partID.gif";
      } 
      else { 
        $filename = "noimage_small.png";
      }
      $picSource = $prefix . $filename;*/
      print("<tr><td><img src='' alt='Img missing'></td><td class='centerTd'>$quantity</td><td>$partName</td> <td class='centerTd'>$colorName</td><td class='centerTd'>$category</td><td class='centerTd'>$partID</td></tr>");
    }
      print("</table>");
      print("</div");
    
  ?>
  </main>

</body>

</html>


