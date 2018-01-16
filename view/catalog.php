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
<title>Katalog</title>
</head>

<body>

<?php 
include_once "header.php";
include_once "menuwrapper.php";
$_SESSION['category']=$_GET["value"];
$category=$_SESSION['category'];
?>

<ul class="breadcrumb">
    <li><a href="index.php">Home</a></li>
    <li><a href="damskie.php">Obuwie</a></li>
    <?php 
    echo "<li><a href='catalog.php?value=$category' style='font-weight:bold'>$category</a></li>"
    ?>
</ul>

<div id="catalog_des">

<?php
try
{
    require_once("../PHP/db.php");

    $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

    $sql="SELECT id_product, price, quantity, file_name, size, total_amount, cat.description, p.name FROM product as p 
            inner join carton as c on p.id_carton=c.id_carton 
            inner join size as s on p.id_size=s.id_size
            inner join image as i on p.id_image=i.id_image
            inner join category as cat on p.id_category=cat.id_category
            where cat.name='$category'";
    $id_product=array();
    $price=array();
    $quantity=array();
    $file_name=array();
    $size=array();
    $desc=array();
    $name=array();
    $amount=array();

    foreach ($conn->query($sql) as $row) 
    {
        array_push($id_product,$row['id_product']);
        array_push($price,$row['price']);
        array_push($quantity,$row['quantity']);
        array_push($file_name,$row['file_name']);
        array_push($size,$row['size']);
        array_push($desc,$row['description']);
        array_push($name,$row['name']);
        array_push($amount,$row['total_amount']);
    }
    $max=count($id_product);

}
catch(PDOException  $e )
{
echo "Error: ".$e;
}
?>

    <h2><?php echo $category ?></h2>
    <p><?php echo $desc[0] ?></p>

</div>

<div class="catalog_page">
<?php

for($i=0;$i<=$max-1;$i++)
if($amount[$i] != 0)
{            
    echo "
    <div>
        <a href='product.php?name=$name[$i]&category=$category'><img id='catalog_img' src=$file_name[$i]></a>
        <div id='catalog_price'><p>$price[$i] zł</p></div>
        <div id='catalog_price_detail'><p>ilość par: $quantity[$i]</br>rozmiarówka: $size[$i]</p></div>
    </div>";
}
?>
</div>

<?php include_once "footer.php"?>

</body>
</html>