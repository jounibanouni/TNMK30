<?php 
  include 'menu.txt'; 
  
  $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
	if (!$connection) {
		die('MySQL connection error');
	}
   
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  $missingPieces = 0;
?>

<main>
<?php
  if(isset($_GET['setID'])){
    $SetID = $_GET['setID'];

    $query = mysqli_query($connection, "SELECT sets.SetID, sets.Setname, inventory.Quantity, inventory.ItemID, inventory.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM sets, inventory, parts, colors WHERE sets.SetID='$SetID' AND inventory.SetID='$SetID' AND inventory.ItemID=parts.PartID AND colors.ColorID=inventory.ColorID");


    $firstRow = mysqli_fetch_array($query);
    $setName = $firstRow['Setname'];

    $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";

    $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
      
      $imageinfo = mysqli_fetch_array($imagesearch);
      if($imageinfo['has_largejpg']) { 
        $filename = "SL/$SetID.jpg";
      } 
      else if($imageinfo['has_largegif']) { 
        $filename = "SL/$SetID.gif";
      } 
      else { 
        $filename = "noimage_small.png";
      }
      
      $picSource = $prefix . $filename;

    print("<div id='fullSet'>");
    
      print("<h2 class='setHeader'>Name: $setName</h2>");
      print("<h2 class='setHeader'>Set: $SetID</h2>");
      print("<img src='$picSource'/>");

      print("<h2>Pieces you have</h2>");
      print("<table>\n<tr>");
      print("<th>Picture</th><th>Quantity</th><th>Part Name</th> <th>Color</th> <th>Part ID</th> </tr>\n");


      

    
    while($row = mysqli_fetch_array($query)) {
      $quantity = $row['Quantity'];
      $partName = $row['Partname'];
      $partID = $row['PartID'];
      $colorName = $row['Colorname'];
      $colorID = $row['ColorID'];
      $itemID = $row['ItemID'];

      $result = mysqli_query($connection, "SELECT parts.PartID, colors.ColorID FROM sets, collection, parts, colors, inventory WHERE inventory.ItemID=parts.PartID AND sets.SetID=collection.SetID AND sets.SetID=inventory.SetID AND inventory.ColorID=colors.ColorID AND parts.PartID='$partID' AND colors.ColorID='$colorID' ORDER BY parts.PartID");
      
      if(mysqli_num_rows($result) == 0){
        $missingPieces += 1;

    
      }
      else{
      //Kolla om bitarna i $SetID från ovan^ finns i din samling
      

      $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
      $SetID = $row['SetID'];
      $setName = $row['Setname'];
      
      $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
      
      $imageinfo = mysqli_fetch_array($imagesearch);
      if($imageinfo['has_jpg']) { 
        $filename = "P/$colorID/$itemID.jpg";
      } 
      else if($imageinfo['has_gif']) { 
        $filename = "P/$colorID/$itemID.gif";
      } 
      else { 
        $filename = "noimage_small.png";
      }

      $picSource = $prefix . $filename;

      print("<tr><td><img src='$picSource' alt='Img missing'></td><td class='centerTd'>$quantity</td><td>$partName</td> <td class='centerTd'>$colorName</td> <td class='centerTd'>$partID</td></tr>");
    }

    }
    if(!null){
      mysqli_data_seek($result, 0);
    }

    print("</table>");
    

    //missing pieces
    if($missingPieces != 0){
      print("<h2>Missing pieces</h2>");
      print("<table>\n<tr>");
      print("<th>Picture</th><th>Quantity</th><th>Part Name</th> <th>Color</th> <th>Part ID</th> </tr>\n");
    }
    $query2 = mysqli_query($connection, "SELECT sets.SetID, sets.Setname, inventory.Quantity, inventory.ItemID, inventory.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM sets, inventory, parts, colors WHERE sets.SetID='$SetID' AND inventory.SetID='$SetID' AND inventory.ItemID=parts.PartID AND colors.ColorID=inventory.ColorID");

    while($row2 = mysqli_fetch_array($query2)) {
      $quantity = $row2['Quantity'];
      $partName = $row2['Partname'];
      $partID = $row2['PartID'];
      $colorName = $row2['Colorname'];
      $colorID = $row2['ColorID'];
      $itemID = $row2['ItemID'];

      $result = mysqli_query($connection, "SELECT parts.PartID, colors.ColorID FROM sets, collection, parts, colors, inventory WHERE inventory.ItemID=parts.PartID AND sets.SetID=collection.SetID AND sets.SetID=inventory.SetID AND inventory.ColorID=colors.ColorID AND parts.PartID='$partID' AND colors.ColorID='$colorID' ORDER BY parts.PartID");
      
      if(mysqli_num_rows($result) == 0){
        $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
        $SetID = $row2['SetID'];
        $setName = $row2['Setname'];
        
        $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
        
        $imageinfo = mysqli_fetch_array($imagesearch);
        if($imageinfo['has_jpg']) { 
          $filename = "P/$colorID/$itemID.jpg";
        } 
        else if($imageinfo['has_gif']) { 
          $filename = "P/$colorID/$itemID.gif";
        } 
        else { 
          $filename = "noimage_small.png";
        }
  
        $picSource = $prefix . $filename;
  
        print("<tr><td><img src='$picSource' alt='Img missing'></td><td class='centerTd'>$quantity</td><td>$partName</td> <td class='centerTd'>$colorName</td> <td class='centerTd'>$partID</td></tr>");
      }
      else{
        //Kolla om bitarna i $SetID från ovan^ finns i din samling
      



      }
}
print("</table>");


//missing pieces
//if($missingPieces == 0){
  print("<h2>Pieces you have but in a different color</h2>");
  print("<table>\n<tr>");
  print("<th>Picture</th><th>Quantity</th><th>Part Name</th> <th>Color</th> <th>Part ID</th> </tr>\n");
//}
$query3 = mysqli_query($connection, "SELECT sets.SetID, sets.Setname, inventory.Quantity, inventory.ItemID, inventory.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM sets, inventory, parts, colors WHERE sets.SetID='$SetID' AND inventory.SetID='$SetID' AND inventory.ItemID=parts.PartID AND colors.ColorID=inventory.ColorID");

while($row = mysqli_fetch_array($query3)) {
  $quantity = $row['Quantity'];
  $partName = $row['Partname'];
  $partID = $row['PartID'];
  $colorName = $row['Colorname'];
  $colorID = $row['ColorID'];
  $itemID = $row['ItemID'];

  $result = mysqli_query($connection, "SELECT parts.PartID, colors.ColorID FROM sets, collection, parts, colors, inventory WHERE inventory.ItemID=parts.PartID AND sets.SetID=collection.SetID AND sets.SetID=inventory.SetID AND inventory.ColorID=colors.ColorID AND parts.PartID='$partID' ORDER BY parts.PartID");
  
  while($row4 = mysqli_fetch_array($result)) {
    $wrongColorArr = array($row4['ColorID']); //Array stores wrong colors of piece 
  }

  if(mysqli_num_rows($result) == 0){
    $missingPieces += 1;
  }
  else{
  //Kolla om bitarna i $SetID från ovan^ finns i din samling
  

  $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
  $SetID = $row['SetID'];
  $setName = $row['Setname'];
  
  $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
  
  $imageinfo = mysqli_fetch_array($imagesearch);
  if($imageinfo['has_jpg']) { 
    $filename = "P/$colorID/$itemID.jpg";
  } 
  else if($imageinfo['has_gif']) { 
    $filename = "P/$colorID/$itemID.gif";
  } 
  else { 
    $filename = "noimage_small.png";
  }

  $picSource = $prefix . $filename;

 print("<tr><td><img src='$picSource' alt='Img missing'></td><td class='centerTd'>$quantity</td><td>$partName</td> <td class='centerTd'>");/* 
  for($i = 0; $i < sizeof($wrongColorArr); $i++){
    $wrongColorID = $wrongColorArr[$i];
    $query4 = mysqli_query($connection, "SELECT colors.Colorname FROM colors WHERE colors.ColorID='$wrongColorID'");

    while($row4 = mysqli_fetch_array($query4)) {
      $colorName = $row4['Colorname'];
      print($colorName); 
    }
  }  koplla ihop färgkod med namn */
  print("</td> <td class='centerTd'>$partID</td></tr>");
}

}
print("</table>");
print("</div");


}

  else{

    $query = mysqli_query($connection, "SELECT sets.SetID, sets.Setname FROM sets, collection WHERE collection.SetID=sets.SetID LIMIT 20");



    while($row = mysqli_fetch_array($query)) {
      
      $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
      $SetID = $row['SetID'];
      $setName = $row['Setname'];
      
      $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
      
      $imageinfo = mysqli_fetch_array($imagesearch);
      if($imageinfo['has_largejpg']) { 
        $filename = "SL/$SetID.jpg";
      } 
      else if($imageinfo['has_largegif']) { 
        $filename = "SL/$SetID.gif";
      } 
      else { 
        $filename = "noimage_small.png";
      }

      $picSource = $prefix . $filename;

      $query2 = mysqli_query($connection, "SELECT parts.Partname, inventory.Quantity FROM parts, inventory WHERE inventory.SetID='$SetID' AND inventory.ItemID=parts.PartID LIMIT 5");
      
      print('<div class="setBox">');
      print("<p>Set: $SetID</p> <p>$setName</p>");
      print("<img class='setPic' src=\"$picSource\" alt='Picture of Set' />");
      print('<ul class="setList">');
      while($row2 = mysqli_fetch_array($query2)) {
        $partName = $row2['Partname'];
        $quantity = $row2['Quantity'];
        print("<li>$quantity x $partName</li>");
      }
      print('</ul>');
      print("<a class='readMore' href='index.php?setID=$SetID'><p>Read More...</p></a>");
      print('</div>');
      
    }
  }
    ?>
  
</main>

</body>

</html>





<!-- <div>Font made from <a href="http://www.onlinewebfonts.com">oNline Web Fonts</a>is licensed by CC BY 3.0</div> -->