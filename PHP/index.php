<?php 
  include 'menu.txt'; 
  
  $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
			if (!$connection) {
				die('MySQL connection error');
			}
      
      $setArr = array();
      
      $query = mysqli_query($connection, "select sets.Setname from sets, collection WHERE collection.SetID=sets.SetID");

      while($res = mysql_fetch_array($query)) {
        $setArr[] = $res;
      } 

      

?>

<main>
  <div class="setBox">
    <p>Sats: Saturn</p>
    <img class="setPic" src="" alt="Picture of Set">
    <ul class="setList">
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
      <li>3x Yellow Cube 2x2</li>
    </ul>
  </div>


</main>

</body>

</html>





<!-- <div>Font made from <a href="http://www.onlinewebfonts.com">oNline Web Fonts</a>is licensed by CC BY 3.0</div> -->