<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


$kullanici="root";
$sifre="";
try {
	$db = new PDO('mysql:host=localhost;dbname=aaa;charset=utf8',$kullanici,$sifre);
} catch (Exception $e) {
	echo 'hata :'. $e->getMessage();
}

$aranan = htmlspecialchars($_POST['aranan']);
$secenek = htmlspecialchars($_POST['sec']);


$sayfada = 10;
$limit=0;



$sorgu2 = "SELECT * FROM dbmevzuat WHERE id LIKE 'idye harf gelmicek :D'";
	$sonuc2 = $db ->prepare($sorgu2);
	$sonuc2 ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc2 ->execute();

$sorgu = "SELECT * FROM dbmevzuat WHERE id LIKE 'idye harf gelmicek :D'";
	$sonuc = $db ->prepare($sorgu);
	$sonuc ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc ->execute();

if(!empty($aranan) && $secenek==1){
	$sorgu = "SELECT * FROM dbmevzuat WHERE baslik LIKE :aranan";
	$sonuc = $db ->prepare($sorgu);
	$sonuc ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc ->execute();
    
    
    $sorgu2 = "SELECT baslik,link, LOWER(icerik) AS icerik FROM dbmevzuat WHERE icerik LIKE :aranan";
	$sonuc2 = $db ->prepare($sorgu2);
	$sonuc2 ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc2 ->execute();
    
	
	
}

else if(!empty($aranan) && $secenek==2){
	$sorgu = "SELECT * FROM dbmevzuat WHERE baslik LIKE :aranan";
	$sonuc = $db ->prepare($sorgu);
	$sonuc ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc ->execute();
}

else if(!empty($aranan) && $secenek==3){
	$sorgu2 = "SELECT baslik,link, LOWER(icerik) AS icerik FROM dbmevzuat WHERE icerik LIKE :aranan";
	$sonuc2 = $db ->prepare($sorgu2);
	$sonuc2 ->bindValue(":aranan",'%'.$aranan.'%');
	$sonuc2 ->execute();
}



if($sonuc->rowcount()!=0 || $sonuc2->rowcount()!=0){
    
    $bulunansonuc = $sonuc2->rowcount() + $sonuc->rowcount();
    
    
    
    $toplam_sayfa = ceil($bulunansonuc / $sayfada);
$sayfa = isset($_POST['sayfa']) ? (int) $_POST['sayfa'] : 1;
    
 
if($sayfa < 1) $sayfa = 1; 
if($sayfa > $toplam_sayfa) $sayfa = $toplam_sayfa; 
$limit = ($sayfa - 1) * $sayfada;

 for($s = 1; $s <= $toplam_sayfa; $s++) {
   if($sayfa == $s) { // eğer bulunduğumuz sayfa ise link yapma.
      echo $s . ' '; 
   } else {
   echo '<a href="javascript:;" onclick="arama('.$s.')">' . $s . '</a> ';
    
   }
}  


   // 
    
    
    
    
	
    echo '<div class="alert alert-primary" role="alert">
              '.$bulunansonuc.' Sonuç bulundu.
            </div>';
    
     
    
    if($secenek==3 || $secenek==1){
        $sorgu3 = "SELECT baslik,link, LOWER(icerik) AS icerik FROM dbmevzuat WHERE icerik LIKE :aranan LIMIT :limit,:sayfada";
        $sonuc3 = $db ->prepare($sorgu3);
        $sonuc3 ->bindValue(":aranan",'%'.$aranan.'%');
        $sonuc3 ->bindValue(":limit", $limit , PDO::PARAM_INT);
        $sonuc3 ->bindValue(":sayfada", $sayfada ,PDO::PARAM_INT);
        $sonuc3 ->execute(); 
	foreach ($sonuc3 as $key2) {
        
        //$sonuc = $sonuc->fetch(PDO::FETCH_BOTH);
        //GROUP BY baslik
        //LIMIT 0,1
        
		$deger=$key2["icerik"];
        $deger2 = after ($aranan, $deger, $deger);
        
        
        
        
        
        

        
        
        
        
        
        
            
		if(strlen($deger2) > 150 )
{
     $deger4 = mb_substr($deger2,0,mb_strpos($deger2,' ',150));
      
        }
else {
     $deger4 = $deger2;
}
        


		
		echo '<div class="card my-3">
          <div class="card-header">';
          echo $key2['baslik'];
          echo '</div><div class="card-body">';
          echo "<a href=".$key2["link"].">"  ;
        echo $aranan;  
        echo $deger4;
          echo '</a></div></div>';
	}
   
        
        
        }
        
        
        
        
        
        if($secenek==2 || $secenek==1){
            $icerik_sonuc = $sonuc2->rowcount();
            $icerik_sayfa = ceil($icerik_sonuc/$sayfada);
            $mod = $icerik_sonuc % $sayfada;
            $fark = $sayfada - $mod;
            $sayfa_fark = $sayfa - $icerik_sayfa;
            $eklenecek = $sayfa_fark * 10;
           
            if($icerik_sonuc==0){
                $limit2 = $limit;
                $sayfada2 = $sayfada;
      
            }
            
            elseif($icerik_sayfa>$sayfa){
                $limit2 = 0;
                $sayfada2 = 0;
            }
            elseif($icerik_sayfa==$sayfa){
                $limit2 = 0;
                $mod = $icerik_sonuc % $sayfada;
                $sayfada2 = $sayfada - $mod;
            }
            elseif($icerik_sayfa<$sayfa){
                $limit2 = $fark + $eklenecek - 10;
                $sayfada2 = $sayfada;
                
            }
            
            
            
            
            
            
            $sorgu4 = "SELECT * FROM dbmevzuat WHERE baslik LIKE :aranan LIMIT :limit,:sayfada";
            $sonuc4 = $db ->prepare($sorgu4);
            $sonuc4 ->bindValue(":aranan",'%'.$aranan.'%');
            $sonuc4 ->bindValue(":limit", $limit2 , PDO::PARAM_INT);
            $sonuc4 ->bindValue(":sayfada", $sayfada2 ,PDO::PARAM_INT);
            $sonuc4 ->execute();
            
            
            foreach ($sonuc4 as $key) {
            
        $deger=$key["icerik"];
       
        
        
        
        if(strlen($deger) > 30 )
{
     $deger2 = substr($deger,0,strpos($deger,' ',150));
      
}
else {
     echo $deger;
}
        
        
        
        

        
        echo '<div class="card my-3">
          <div class="card-header">';
          echo $key['baslik'];
          echo '</div><div class="card-body">';
          echo "<a href=".$key["link"].">".$deger2."</a>";
          
          echo '</div></div>';
   
        
    }
    
    
        }
    
    
    
    
   
    
    
    
    
    
    
}
else{
	echo '<div class="alert alert-warning" role="alert">
              sonuç yok
            </div>';
}










function after ($aranan3, $deger6, $degerli)
    {
        if (!is_bool(mb_strpos($deger6, $aranan3))){
            
            return mb_substr($degerli, mb_strpos($deger6,$aranan3)+strlen($aranan3));
        }
        
    else
        return $degerli;
    };



?>