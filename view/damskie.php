<?php
  session_start();
  if(isset($_SESSION['e_observed_already']))
  unset($_SESSION['e_observed_already']);
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Kategorie</title>
</head>

<body>

<?php include_once "header.php"?>

<?php include_once "menuwrapper.php"?>

<ul class="breadcrumb">
    <li><a href="index.php">Home</a></li>
    <li><a href="damskie.php" style="font-weight:bold">Obuwie damskie</a></li>
</ul>

<?php
try
{
    require_once("../PHP/db.php");

    $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

    $sql="SELECT name FROM category";
    $category=array();

    foreach ($conn->query($sql) as $row) 
    {
        array_push($category,$row['name']);
    }
    $max=count($category);

    $file_name=array();
    for($i=0;$i<$max;$i++)
    {
        $sql="Select file_name from image where description like 'category_$category[$i]'";
    
        foreach ($conn->query($sql) as $row) 
        {
            array_push($file_name,$row['file_name']);
        }   
    }
}
catch(PDOException  $e )
{
echo "Error: ".$e;
}

echo "<div class='category_page'>";
for($k=0;$k<$max;$k++)
{
echo "
    <div>
        <a target='_self' href='catalog.php?value=$category[$k]'>
            <img id='category_img1' src=$file_name[$k] alt=$category[$k] title=$category[$k]>
        </a>
    </div>";
}
echo "</div>";

?>
<?php include_once "footer.php"?>

</body>
</html>