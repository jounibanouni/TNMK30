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

if(isset($_GET['search'])){
    $search_value = $_GET['search'];
    $result = mysqli_query($connection, "SELECT sets.SetID, sets.Setname FROM sets WHERE sets.SetID LIKE '%$search_value%' 
    OR sets.Setname LIKE '%$search_value%'"); 



while($row = mysqli_fetch_array($result)) {
  
  $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
  $SetID = $row['SetID'];
  $setName = $row['Setname'];
  
  $imagesearch = mysqli_query($connection, "SELECT * FROM images, sets WHERE ItemTypeID='S' AND SetID='$SetID' AND images.ItemID=sets.SetID");
  
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