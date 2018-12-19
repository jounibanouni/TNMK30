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
 $limit = 20;
 $start_from = ($page-1) * $limit;
 
 
 $query = mysqli_query($connection, "SELECT sets.SetID, sets.Setname FROM sets ORDER BY sets.SetID LIMIT $start_from,".$limit);


 while($row = mysqli_fetch_array($query)) {
   
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



 $query2 = mysqli_query($connection, "SELECT COUNT(sets.SetID) AS total FROM sets");
 $row = $query2->fetch_assoc();
 $total_pages = ceil($row["total"] / $limit); // calculate total pages with results
 $targetpage = "allsets.php";
 $pagination = "";
 $adjacents = 2;
 $prev = $page - 1;                          //previous page is page - 1
 $next = $page + 1;                          //next page is page + 1
 $lastpage = $total_pages;      //lastpage is = total pages / items per page, rounded up.
 $lpm1 = $lastpage - 1;                      //last page minus 

    
    $pagination = "";
    if($lastpage > 1)
    {   
        $pagination .= "<div class=\"pagination\">";
        //previous button
        if ($page > 1) 
            $pagination.= "<a href=\"$targetpage?page=$prev\">previous</a>";
        else
            $pagination.= "<span class=\"disabled\">previous</span>"; 

        //pages 
        if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up
        {   
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
        {
            //close to beginning; only hide later pages
            if($page < 1 + ($adjacents * 2))     
            {
                for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
            }
            //in middle; hide some front and some back
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                //$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                }
                $pagination.= "...";
                //$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
            }
            //close to end; only hide early pages
            else
            {
                $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                //$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                $pagination.= "...";
                for ($counter = $lastpage - ($adjacents * 2); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                }
            }
        }

        //next button
        if ($page < $counter - 1) 
            $pagination.= "<a href=\"$targetpage?page=$next\">next</a>";
        else
            $pagination.= "<span class=\"disabled\">next</span>";
        $pagination.= "</div>\n";     
    }
    
    
        echo " $pagination";
        echo ("Visar ".$limit." av ".$row["total"]);
                       
            
    




?> 

  </main>
  
  </body>
  
</html>