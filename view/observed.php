<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-git.js"></script>
<script type="text/javascript" src="../config\basket.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Obserwowane</title>
</head>
<body>

<?php 
include_once "header.php";
include_once "menuwrapper.php";
if(!isset($_SESSION['login']))
{
    echo "<div style='font-weight:bold;text-align:center;margin-top:30px;margin-bottom:30px'>Nie jesteś zalogowany</div>";
}
else
{
    try
    {
        require_once("../PHP/db.php");

        $login = $_SESSION['login'];

        $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        
        $sql = "SELECT id_user from user where login='$login'";
        foreach ($conn->query($sql) as $row) 
        {
            $id_user = $row['id_user'];
        }

        $sql="SELECT 
                p.id_product as pid_prod, p.name, p.code, cat.name as category_name, p.price, p.total_amount, col.color, cart.quantity, i.file_name, s.size, o.id_product as oid_prod, o.created_at
                FROM product as p
                inner join observed as o on p.id_product=o.id_product 
                inner join carton as cart on p.id_carton=cart.id_carton
                inner join color as col on p.id_color=col.id_color 
                inner join size as s on p.id_size=s.id_size
                inner join image as i on p.id_image=i.id_image
                inner join category as cat on p.id_category=cat.id_category
                where o.id_user='$id_user';";

        $pid_product=array();
        $name=array();
        $code=array();
        $price=array();
        $color=array();
        $quantity=array();
        $file_name=array();
        $size=array();
        $created_at=array();
        $amount=array();
        $oid_product=array();
        $category=array();

        foreach ($conn->query($sql) as $row) 
        {
            array_push($pid_product,$row['pid_prod']);
            array_push($name,$row['name']);
            array_push($code,$row['code']);
            array_push($price,$row['price']);
            array_push($color,$row['color']);
            array_push($quantity,$row['quantity']);
            array_push($file_name,$row['file_name']);
            array_push($size,$row['size']);
            array_push($created_at,$row['created_at']);
            array_push($amount,$row['total_amount']);
            array_push($oid_product,$row['oid_prod']);
            array_push($category,$row['category_name']);
        }
        $max=count($pid_product);

    }
catch(PDOException  $e )
{
echo "Error: ".$e;
}
if(isset($login))
{ 
    echo '
    <div class="observed_container">
    <h1 style="grid-column:2/7">Ulubione</h1>     
       
    <div class="observed_image_header">
        <p>Obraz</p>
    </div>
        
    <div class="observed_description_header">
        <p>Opis</p>
    </div>

    <div class="observed_pair_quantity_header">
        <p>Ilość par</p>
    </div>    
    
    <div class="observed_price_header">
        <p>Cena</p>
    </div>
        
    <div class="observed_availability_header">
        <p>Dostępność</p>
    </div>

    <div class="observed_availability_header_500">
        <p>Dostęp.</p>
    </div>
        
    <div class="observed_add_delete_header">
        <p>Dodaj<span style="display:inline-block">/usuń</span></p>
    </div>                   
    </div>';

    for($i=0;$i<=$max-1;$i++)
    {
        if($amount[$i]>0)
        {
            $icon="<i class='fa fa-check fa-3x' aria-hidden='true' style='color:#00b000'></i>";
        }
        else
        {
            $icon="<i class='fa fa-times fa-3x' aria-hidden='true' style='color:red'></i>";
        }
        echo "
    <div class='observed_product'>
        
    <div class='observed_image'>
        <a href='product.php?name=$name[$i]&category=$category[$i]'><img id='observed_img' src=$file_name[$i]></a>
    </div>
            
    <div class='observed_description'>
        <a style='font-weight:bold' href='product.php?name=$name[$i]&category=$category[$i]'><p>$name[$i]</p></a>    
        <p>kod produktu: $code[$i]</p>
        <p>kolor: $color[$i]</p>
        <p>rozmiarówka: $size[$i]</p>  
        <p style='float:right'>kiedy dodany: $created_at[$i]</p>
    </div>

    <div class='observed_pair_quantity'>
        <p>$quantity[$i]</p>
    </div>  

    <div class='observed_price'>
        <p>$price[$i] zł</p>        
    </div>
        
    <div class='observed_availability'>
        <p>$icon</p>
    </div> 

    <div class='observed_add_delete'>";
    if($amount[$i]>1)
    {
    echo"
        <form action='../PHP/insert_basket.php' target='_self' method='POST'>
            <button type='submit'id='observed_add' name='add_basket' value=$pid_product[$i]>Dodaj<br> do koszyka</button>            
        </form>";
    }
    else
    {
    echo"
        <button id='observed_add_not_post' name='add_basket' value=$pid_product[$i]>Dodaj<br> do koszyka</button>
        ";
    }
    echo "
    <form action='../PHP/delate_observed.php' target='_self' method='POST'>
    <button type='submit' id='observed_delete' name='delete_observed' value=$oid_product[$i]>Usuń z<br> obserwowanych</button>         
    </form>
    </div>

    </div>";
    }  
}
}
include_once "footer.php"?>

</body>
</html>