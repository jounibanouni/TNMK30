<?php 
  include 'menu.txt'; 
  
  $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
	if (!$connection) {
		die('MySQL connection error');
	}
   
?>

<main>
<?php
  if(isset($_GET['setID'])){
    $SetID = $_GET['setID'];
    
    

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
      print("<p>Sats: $SetID</p> <p>$setName</p>");
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
  
    <!--<div class="setBox">
    <p>Sats: Saturn</p>
    <img class="setPic" src="" alt="Picture of Set">
    <ul class="setList">
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
    </ul>
  </div> -->


</main>

</body>

</html>





<!-- <div>Font made from <a href="http://www.onlinewebfonts.com">oNline Web Fonts</a>is licensed by CC BY 3.0</div> -->