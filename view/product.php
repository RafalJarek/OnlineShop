<?php
    session_start();
    $_SESSION['category']=$_GET["category"];
    $getcategory=$_SESSION['category'];
    $_SESSION['name']=$_GET["name"];
    $getname=$_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-git.js"></script>
<script type="text/javascript" src="../config/hide.show.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title><?php echo $_GET["name"] ?></title>
</head>

<?php 

include_once "header.php";
include_once "menuwrapper.php";

try
{
    require_once("../PHP/db.php");

    $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

    $sql="SELECT 
        p.id_product, p.total_amount, p.name, p.code, p.warm, p.description as pdesc, p.price, col.color, t.type, t.height as theight, m.material, cart.weight, cart.height as cheight, cart.width, cart.lenght, car.quantity, i.file_name, cat.description as cdesc, s.size 
        FROM product as p 
        inner join carton as cart on p.id_carton=cart.id_carton
        inner join color as col on p.id_color=col.id_color 
        inner join type as t on p.id_type=t.id_type
        inner join material as m on p.id_material=m.id_material
        inner join carton as car on p.id_carton=car.id_carton
        inner join size as s on p.id_size=s.id_size
        inner join image as i on p.id_image=i.id_image
        inner join category as cat on p.id_category=cat.id_category
        where p.name='$getname'";

    foreach ($conn->query($sql) as $row) 
    { 
        $id_product = $row['id_product'];
        $name = $row['name'];
        $code = $row['code'];
        $warm = $row['warm'];
        $pdesc = $row['pdesc'];
        $price = $row['price'];
        $color = $row['color'];
        $type = $row['type'];
        $theight = $row['theight'];
        $material = $row['material'];
        $weight = $row['weight'];
        $cheight = $row['cheight'];
        $width = $row['width'];
        $lenght = $row['lenght'];
        $quantity = $row['quantity'];
        $file_name =$row['file_name'];
        $cdesc = $row['cdesc'];
        $size = $row['size'];
        $amount= $row['total_amount'];
    }

    $sql="SELECT id_category FROM category where name='$getcategory'";

    foreach ($conn->query($sql) as $row) 
    {
        $id_category = $row['id_category'];
    }

    $sql="SELECT 
        c.name as category, p.name as product, file_name 
        FROM product as p 
        inner join category as c on c.id_category=p.id_category
        inner join image as i on i.id_image=p.id_image
        where code='$code' AND c.id_category='$id_category' AND p.name!='$getname' limit 4";

    $col_category=array();
    $col_name=array();
    $col_file_name=array();

    foreach ($conn->query($sql) as $row) 
    {
    array_push($col_category,$row['category']);
    array_push($col_name,$row['product']);
    array_push($col_file_name,$row['file_name']);
    }
    $max=count($col_name);

    $sql="SELECT file_name FROM image where description LIKE '$code $color%'";
    
    $pic_file_name=array();

    foreach ($conn->query($sql) as $row) 
    {
    array_push($pic_file_name,$row['file_name']);
    }
    $max_pic=count($pic_file_name);
}
catch(PDOException  $e )
{
echo "Error: ".$e;
}
?>
<?php
echo "
<ul class='breadcrumb'>
    <li><a href='index.php'>Home</a></li>
    <li><a href='damskie.php'>Obuwie</a></li>
    <li><a href='catalog.php?value=$getcategory'> $getcategory </a></li>
    <li><a href='product.php?name=$getname&category=$getcategory' style='font-weight:bold'> $getname </a></li>    
</ul>";
?>
<?php
    $niedostepne = '../Img/niedostepne.png';
     if($file_name == $niedostepne)
     {
        $pic_file_name[0]=$niedostepne;
        $pic_file_name[1]=$niedostepne;
        $pic_file_name[2]=$niedostepne;
        $pic_file_name[3]=$niedostepne;
     }
    echo "
    <div class='product_container'>        
    
     <div class='product_container_img'>
 
         <div class='product_img'>
             <img id='product_img_main' src=$file_name>
         </div>";
     
         if(isset($pic_file_name[0]) && isset($pic_file_name[1]) && isset($pic_file_name[2]) && isset($pic_file_name[3]))
        {   
        echo"
         <label id='product_img_choose'>
            
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[0]' onclick='changeimage1()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[1]' onclick='changeimage2()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[2]' onclick='changeimage3()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[3]' onclick='changeimage4()'/>
            
            <script>
            function changeimage1()
            {      
                document.getElementById('product_img_main').src='$pic_file_name[0]';
            }
            function changeimage2()
            {      
                document.getElementById('product_img_main').src='$pic_file_name[1]';
            }
            function changeimage3()
            {      
                document.getElementById('product_img_main').src='$pic_file_name[2]';
            }
            function changeimage4()
            {      
                document.getElementById('product_img_main').src='$pic_file_name[3]';
            }
            </script>

         </label>";
        }
         echo "
         
     </div>


     

 
     <div class='product_detail'>
         <h2>$name</h2>
         
         <div class='product_container_img_500'>
 
         <div class='product_img'>
             <img id='product_img_main_500' src=$file_name>
         </div>";
     
         if(isset($pic_file_name[0]) && isset($pic_file_name[1]) && isset($pic_file_name[2]) && isset($pic_file_name[3]))
        {   
        echo"
         <label id='product_img_choose'>

            <input type='image'  class='input_product_img_choose' src='$pic_file_name[0]' onclick='changeimage1_500()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[1]' onclick='changeimage2_500()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[2]' onclick='changeimage3_500()'/>
            <input type='image'  class='input_product_img_choose' src='$pic_file_name[3]' onclick='changeimage4_500()'/>
            
            <script>
            function changeimage1_500()
            {      
                document.getElementById('product_img_main_500').src='$pic_file_name[0]';
            }
            function changeimage2_500()
            {      
                document.getElementById('product_img_main_500').src='$pic_file_name[1]';
            }
            function changeimage3_500()
            {      
                document.getElementById('product_img_main_500').src='$pic_file_name[2]';
            }
            function changeimage4_500()
            {      
                document.getElementById('product_img_main_500').src='$pic_file_name[3]';
            }
            </script>

         </label>";
        }
         echo "
     </div>

         <div id='kod_product'>
             <p >Kod produktu: 
                 <span style='font-weight:bold'>$code</span>
             <p style='padding: 0 8% 0 8%'>Kolor:
                 <span style='font-weight:bold'>$color</span>
             </p>
             <p style='display:inline-block'>rozmiarówka: 
                 <span style='font-weight:bold'>$size</span>
             </p>
         </div>

            <div id='product_price' style='display: inline-block'>
                <p id='quan' style='display: inline-block'>
                    $quantity
                </p>

                <p style='display: inline-block'>
                    x 
                </p>

                <p id='pri' style='display: inline-block'>
                    $price
                </p>

                <p style='display: inline-block'>
                    zł
                </p>

                <p style='padding: 0 10px 0 10px;display: inline-block'>
                    =
                </p>

                <p class='asdprice' style='display: inline-block'>
                    <span id='asdprice'>suma</span><span> zł</span> 
                </p>

         </div>
         
         <div id='product_price_detail'>
             <p>(cena podana jest w brutto)</p>
         </div>
         
         <div id='cartons_amount'>
             <div id='carton_amount_text'>Ilość kartonów</div>
             <form action='../PHP/insert_basket.php' method='post' id='form1'>
             <input class='1' id='choose_button' type='text' name='quant' value='1' onclick='changequantity(1)'>
             <input class='2' id='choose_button' type='button' name='quant' value='2' onclick='changequantity(2)'>
             <input class='3' id='choose_button' type='button' name='quant' value='3' onclick='changequantity(3)'>
             <input class='4' id='choose_button' type='button' name='quant' value='4' onclick='changequantity(4)'>
             <input class='5' id='choose_button' type='button' name='quant' value='5' onclick='changequantity(5)'>
             <input type='hidden' name='add_basket' value=$id_product>
             </form>
         </div>
         
         <script>
         document.getElementById('asdprice').innerHTML = (parseFloat(document.getElementById('quan').innerHTML) * parseFloat(document.getElementById('pri').innerHTML)).toFixed(2);
         function changequantity(value)
         {  
            if(value==1)
            {
                $('.1').clone().prop('type','text').insertAfter('.1').prev().remove();
                $('.2').clone().prop('type','button').insertAfter('.2').prev().remove();
                $('.3').clone().prop('type','button').insertAfter('.3').prev().remove();
                $('.4').clone().prop('type','button').insertAfter('.4').prev().remove();
                $('.5').clone().prop('type','button').insertAfter('.5').prev().remove();
            }
            else if(value==2)
            {
                $('.1').clone().prop('type','button').insertAfter('.1').prev().remove();
                $('.2').clone().prop('type','text').insertAfter('.2').prev().remove();
                $('.3').clone().prop('type','button').insertAfter('.3').prev().remove();
                $('.4').clone().prop('type','button').insertAfter('.4').prev().remove();
                $('.5').clone().prop('type','button').insertAfter('.5').prev().remove();
            }
            else if(value==3)
            {
                $('.1').clone().prop('type','button').insertAfter('.1').prev().remove();
                $('.2').clone().prop('type','button').insertAfter('.2').prev().remove();
                $('.3').clone().prop('type','text').insertAfter('.3').prev().remove();
                $('.4').clone().prop('type','button').insertAfter('.4').prev().remove();
                $('.5').clone().prop('type','button').insertAfter('.5').prev().remove();
            }
            else if(value==4)
            {
                $('.1').clone().prop('type','button').insertAfter('.1').prev().remove();
                $('.2').clone().prop('type','button').insertAfter('.2').prev().remove();
                $('.3').clone().prop('type','button').insertAfter('.3').prev().remove();
                $('.4').clone().prop('type','text').insertAfter('.4').prev().remove();
                $('.5').clone().prop('type','button').insertAfter('.5').prev().remove();
            }
            else if(value==5)
            {
                $('.1').clone().prop('type','button').insertAfter('.1').prev().remove();
                $('.2').clone().prop('type','button').insertAfter('.2').prev().remove();
                $('.3').clone().prop('type','button').insertAfter('.3').prev().remove();
                $('.4').clone().prop('type','button').insertAfter('.4').prev().remove();
                $('.5').clone().prop('type','text').insertAfter('.5').prev().remove();
            }
            var getvalue = value;
            {    
            document.getElementById('asdprice').innerHTML = (parseFloat(document.getElementById('quan').innerHTML) * parseFloat(document.getElementById('pri').innerHTML) * getvalue).toFixed(2);
            }
        }
         </script>";
         if(!empty($col_name))
         {
            echo "<label id='color_list'>Kolor";
            for($i=0;$i<$max;$i++)
            {
                $j=$i+1;
                echo "
                <div id='kolor$j'><a href='product.php?name=$col_name[$i]&category=$col_category[$i]' style='font-weight:bold'><img src=$col_file_name[$i]></a></div>";
            }
            echo "</label>";
         }

         echo "
         </label>
         <div style='margin-top:10px' id='add_buttons'>";
         if($amount >0)
         { echo"
                <button type='submit' id='add_basket' form='form1'>Dodaj do koszyka</button>";
         }
         else
         {
            echo "<div id='nie_na_stanie'>Nie mamy chwilowo na stanie</div>";
         }
            echo"
            <form action='../PHP/insert_observed.php' target='_self' method='POST'>
               <button type='submit' id='add_favourite' name='add_observed' value=$id_product>Dodaj do obserwowanych</button>         
            </form>
         </div>
     
 
        <div class='products_description'>
            <div class='div_button_show'>
                <button id='button_show_product_description' value='Show'><div id='products_description_header'>Opis produktu</div></button>
            </div>
                
            <p id='product_description'>
                $pdesc</br></br>";
                if($warm == 1)
                {
                    echo "Obuwie jest ocieplane<br><br>"; 
                }
                elseif($warm == 0)
                {
                    echo "Obuwie nie jest ocieplane<br><br>"; 
                }
                if($theight>0)
                echo"
                Wysokość obcasa: $theight cm</br></br>";
                echo "
                Materiał: $material
            </p>
            
            <div class='div_button_show'>
                <button id='button_show_carton_description' value='Show'><div id='products_description_header'>Opis kartonu</div></button>
            </div>
            
            <p id='carton_description'>Wymiary kartonu to $cheight cm x $width cm x $lenght cm. Waży on $weight kg.</p>
    
            <div class='div_button_show'>
                <button id='button_show_shipment_description' value='Show'><div id='products_description_header'>Termin dostawy</div></button>
            </div>       
                
            <p id='shipment_description'>
                <span style='text-decoration:underline'>Produkty z naszego sklepu przekazywane są do realizacji natychmiast.</span></br></br>
                <span style='font-weight: bold'>Zamówienia z przedpłatą na konto:</span> paczki nadajemy w dniu zaksięgowania płatności (musi ona znaleźć się na naszym koncie do godz. 13:30). Przelewy zaksięgowane po tej godzinie, przechodzą do realizacji w następnym dniu roboczym.</br></br>
                <span style='font-weight: bold'>Zamówienia za pobraniem:</span> realizujemy dniu złożenia zamówienia (zamówienie musi zostać złożone do godz. 13:30). Zamówienia złożone po tej godzinie, przechodzą do realizacji w następnym dniu roboczym.
            </p>
            
            <div class='div_button_show'>
                <button id='button_show_return_description' value='Show'><div id='products_description_header'>Zwroty i wymiany</div></button>
            </div>      
            
            <p id='return_description'>Na zwrot lub wymianę towaru masz 30 dni. Nie musisz podawać nam przyczyny - takie masz prawo :) Wystarczy, że klikniesz zakładkę 'Wymiana i zwroty' na stronie głównej i będziesz postępować zgodnie ze wskazówkami. Wygenerujesz tam formularz zwrotu, który dołączysz do odsyłanego towaru. Czas w jakim otrzymasz zwrot pieniędzy to max 3 dni od otrzymania przez nas przesyłki.</p>
        </div>
    </div>
 </div>
</div>";

//<input id='block3' type='text' name='text' placeholder='ile?' size='2'>


include_once 'footer.php'
?>
</body>
</html>