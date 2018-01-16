
<?php
require_once "db.php";

//Database connection.

$con = MySQLi_connect($host, $db_user, $db_password, $db_name);
  
    //Check connection
    
    if (MySQLi_connect_errno()) {
    
      echo "Failed to connect to MySQL: " . MySQLi_connect_error();
    
    }

//Getting value of "search" variable from "script.js".

if (isset($_POST['search'])) {

//Search box value assigning to $Name variable.

   $Name = $_POST['search'];

//Search query.

   $Query = "SELECT 
   p.name as name, c.name as category 
   FROM product as p
   inner join category as c on c.id_category=p.id_category
   WHERE p.name LIKE '%$Name%' LIMIT 5;";

//Query execution

mysqli_set_charset($con, "utf8"); 
   $ExecQuery = MySQLi_query($con, $Query);
   
//Creating unordered list to display result.

   echo '

<ul style="cursor: pointer;">

   ';

   //Fetching result from database.

   while ($Result = MySQLi_fetch_array($ExecQuery)) {
$category=$Result['category'];
$name=$Result['name']
       ?>

   <!-- Creating unordered list items.

        Calling javascript function named as "fill" found in "script.js" file.

        By passing fetched result as parameter. -->

   <li onclick='fill("<?php echo $category; ?>")'>

<?php 
echo "<a href='../view/product.php?name=$name&category=$category' style='font-weight:bold'>"
?>
   <!-- Assigning searched result in "Search box" in "search.php" file. -->

       <?php echo $name; ?>

   </li></a>

   <!-- Below php code is just for closing parenthesis. Don't be confused. -->

   <?php

}}


?>

