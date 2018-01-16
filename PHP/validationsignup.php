<?php
    //udana walidacja? Załóżmy, że tak!
    $all_good=true;

    //poprawny login?
    $login = $_POST['login'];

    //sprawdzenie dlugosci loginu
    if((strlen($login)<3) || (strlen($login)>20))
    {
      $all_good=false;
      $_SESSION['e_login_strlen']="Login musi posiadać od 3 do 20 znaków";
    }

    //sprawdzenie znakow loginu
    $loginmatch = preg_match('#^[0-9a-zA-ZsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $login);

    if($loginmatch != 1)
    {
      $all_good=false;
      $_SESSION['e_namematch']="login może składać się tylko z liter i cyfr";
    }

    //sprawdzenie poprawności email
    $email = $_POST['email'];
    $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailS,FILTER_VALIDATE_EMAIL)==false) || ($emailS!=$email))
    {
      $all_good=false;
      $_SESSION['e_email']="Podaj poprawny adres e-mail";
    }

    //poprawne hasło?
    if(isset($_POST['haslo1']))
    {
      $haslo1 = $_POST['haslo1'];
      $haslo2 = $_POST['haslo2'];

      //sprawdzenie długości hasła
      if((strlen($haslo1)<8) || (strlen($haslo1)>20))
      {
        $all_good=false;
        $_SESSION['e_haslo_strlen']="Hasło musi posiadać od 8 do 20 znaków";
      }

      //czy hasła takie same
      if($haslo1!=$haslo2)
      {
        $all_good=false;
        $_SESSION['e_haslo_thesame']="Podane hasła nie są identyczne";
      }

      //hashowanie hasła
      $salt = uniqid(mt_rand(), true);
      $haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);
    }
    //checkbox regulamin
    if(!isset($_POST['regulamin']))
    {
      $all_good=false;
      $_SESSION['e_regulamin']="Potwierdź akceptację regulaminu";
    }

    if(isset($_POST['g-recaptcha-response']))
    {
      //bot or not?                                                             zmiana recaptcha przy zmianie domeny
      $secret="6Lcy3zwUAAAAAEe-cevgc-IqHYH-1aiSkaMzs2vE";

      $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
      
      $response = json_decode($check);

      if(!($response->success))
      {
        $all_good=false;
        $_SESSION['e_bot']="Potwierdź, że nie jesteś botem";
      }
    }

    //sprawdznie imienia
    $firstname = $_POST['firstname'];
    $firstnamematch = preg_match('#^[a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $firstname);
    //\s
    //$namespace = preg_replace('/\s+/S',"", $name);

    if($firstnamematch != 1)
    {
      $all_good=false;
      $_SESSION['e_firstname']="Imie musi składać się tylko z liter i nie można użyć spacji";
    }

    //sprawdznie nazwiska
    $surname = $_POST['surname'];
    $surnamematch = preg_match('#^[a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $surname);
    //\s
    //$namespace = preg_replace('/\s+/S',"", $name);

    if($surnamematch != 1)
    {
      $all_good=false;
      $_SESSION['e_surname']="Nazwisko musi składać się tylko z liter";
    }

    //sprawdzenie nazwy firmy
    $companyname = $_POST['companyname'];
    
    $companynamematch = preg_match('#^[0-9a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{1}+[\s0-9a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $companyname);

    if($companynamematch != 1)
    {
      $all_good=false;
      $_SESSION['e_companyname']="Nazwa firmy może składać się tylko z liter i cyfr";
    }

    //sprawdzenie nipu
    $nip = $_POST['nip'];
    $nipreplace= str_replace("-","",$nip);
    
    function NIPIsValid($nip) {
      if(!empty($nip)) {
                $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
                $nipmatch = preg_replace('/[\s-]/', '', $nip);
                if (strlen($nipmatch) == 10 && is_numeric($nipmatch)) {	 
                    $sum = 0;
                    for($i = 0; $i < 9; $i++)
                        $sum += $nipmatch[$i] * $weights[$i];
                    return ($sum % 11) == $nipmatch[9];
                }
      }
            return false;
        }

    if(NIPIsValid($nip) == false)
    {
      $all_good=false;
      $_SESSION['e_nip']="Zły nip. Nip musi składać się tylko z cyfr i myślnika";
    }

    //sprawdzenie telefonu
    $phone = $_POST['phone'];

    if($phonematch = preg_match('/^[1-9]{1}+[0-9]{8}$/',$phone)==false)
    {
      $all_good=false;
      $_SESSION['e_phone']="Zły numer podaj 9 cyfrowy numer. Jeśli to telefon stacjonarny to podaj z numerem kierunkowym bez 0";
    }
    
    //sprawdzenie telefonu
    $country = $_POST['country'];
     
    //sprawdzenie miejscowości
    $city = $_POST['city'];

    $citymatch = preg_match('#^[\sA-Za-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]*$#', $city);

    if($citymatch != 1)
    {
      $all_good=false;
      $_SESSION['e_city']="Miejscowość musi składać się tylko z liter";
    }

    //sprawdzenie kodu pocztowego
    $postcode = $_POST['postcode'];

    $postcodematch = preg_match('#^[0-9]{2}+[-]{1}+[0-9]{3}+$#ui', $postcode);

    if($postcodematch != 1)
    {
      $all_good=false;
      $_SESSION['e_postcode']="Składnia kodu pocztowego to _ _-_ _ _";
    }

    //sprawdzenie ulicy
    $street = $_POST['street'];
    
    $streetmatch = preg_match('#^[0-9\sA-Za-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]*$#', $street);
    
    if($streetmatch != 1)
    {
      $all_good=false;
      $_SESSION['e_street']="Ulica musi składać się tylko z liter albo cyfr";
    }
    
    //sprawdzenie numeru budynku
    $buildingnumber = $_POST['buildingnumber'];
        
    $buildingnumbermatch = preg_match('#^[0-9a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $buildingnumber);
        
    if($buildingnumbermatch != 1)
    {
      $all_good=false;
      $_SESSION['e_buildingnumber']="Numer budynku musi składać się tylko z liter i cyfr";
    }

    //sprawdzenie numeru lokalu
    $localnumber = $_POST['localnumber'];

    $localnumbermatch = preg_match('#^[0-9a-zsąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$#ui', $localnumber);

    if($localnumbermatch != 1 && $localnumber != null)
    {
    $all_good=false;
    $_SESSION['e_localnumber']="Numer lokalu musi składać się tylko cyfr";
    }
    else
    {
      $localnumber='';
    }

?>