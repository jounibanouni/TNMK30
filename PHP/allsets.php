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


 
 if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
 $result_per_page = 20;
 $start_from = ($page-1) * $result_per_page;
 
 $query = mysqli_query($connection, "SELECT sets.SetID, sets.Setname FROM sets LIMIT $start_from,".$result_per_page);


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

 $query2 = mysqli_query($connection, "SELECT COUNT(sets.SetID) AS total FROM sets");
 $row = $query2->fetch_assoc();
 $total_pages = 100; //ceil($row["total"] / $results_per_page); // calculate total pages with results
 
 for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
             echo "<a href='allsets.php?page=".$i."'";
             if ($i==$page)  echo " class='curPage'";
             echo ">".$i."</a> "; 
 }





?>  
  </main>
  
  </body>
  
  </html>