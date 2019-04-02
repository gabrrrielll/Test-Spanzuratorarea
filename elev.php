<div id ="intrebari">
    <form method='POST' action="<?php echo $PHP_SELF; ?>">
        <?php session_start();
        
        global $mesaj;
        
        // 1.a) extragem informatia din fisierul de intrebari
        $f_intrebari = file("intrebari.txt");
        // 1.b) extragem informatia din fisierul de raspunsuri
        $f_raspunsuri = file("raspunsuri.txt");
        // 1.c) extragem informatia din fisierul de bife
        $f_check = file("checkbox.txt");
      
        //extragem valorile intrebarilor din fisier
        global $intrebari;
        $intrebari = explode("|",$f_intrebari[0]);
        
        //extragem numarul intrebarilor
        $nr_intr = count($intrebari);
        
        //extragem valorile raspunsurilor din fisier
        $total_raspunsuri = explode("|",$f_raspunsuri[0]);
        
        //extragem valorile campurilor cu check din fisier
        $total_check = explode("|",$f_check[0]);
        
        //extragem valorile check din submit-ul trecut deoarece variabila POST se incarca abia dupa submit 
        $checked_anterior= explode("*", $total_check[$_SESSION['numar']-1]);
        
        //initializam o sesiune de numarare a intrebarilor
        if(!isset( $_SESSION['numar'])){
              $_SESSION['numar'] = 0;
        } 
        
        /*initializam o sesiune de incrementare a raspunsuri gresite - imaginilor de spanzurare 
        si o folosim intr-o variabila pe care o putem folosi in adresa imaginii */
        if(!isset($_SESSION['nr_intr'])){
            $_SESSION['nr_intr'] = 0;
        }
        
        //cream o variabila globala care sa detina si pozitia off acolo unde nu s-a facut check
        foreach($checked_anterior as $k=>$v) {
            if($_POST['check'][$k] == 'on'){ 
                $_SESSION['check'][$k] = 'on';
            }else{
                $_SESSION['check'][$k] = 'off';
            } 
                
        }
        
            if(!isset($_SESSION['buton'])){
             //raspunsuri gresite
            $greseli = 5 - $_SESSION['nr_intr'];
            $_SESSION['buton'] = '<input type= "submit" name="submit" value="Incepe">';
            $mesaj = "<h4 style='color: blue;'>Esti gata sa iti risti viata raspunzand la niste intrebari?</h4><h3>Ai dreptul la $greseli greseli</h3>";
            }
            
            if($_POST['submit'] == "Incepe"){
                
                $_SESSION['numar'] = 0;
                $_SESSION['nr_intr'] = 0;
                $nr = 0;
                $_POST['check'] = [];
                $_SESSION['check'] = [];
                $greseli = 5 - $_SESSION['nr_intr'];
                $_SESSION['buton'] = '<input type= "submit" name="submit" value="Raspunde">';
                $mesaj = "<h3 style='color: blue;'>Ai grija ce raspunzi daca tii la viata ta!!</h3><h3>Ai dreptul la $greseli greseli</h3>";
            }
            
            if( $_SESSION['nr_intr'] ==4 && $_SESSION['numar'] > 1){
                $mesaj = "<h1 style='color: red;'>GATA! AI FOST SPANZURAT!</h1>";
                $_SESSION['buton'] = '<input type= "submit" name="submit" value="Incepe">';
                $nr =5;
                $_SESSION['nr_intr']=4;
            
            }elseif($_SESSION['numar'] >= $nr_intr && $_SESSION['numar'] >1){
                $mesaj = "<h1 style='color: green;'>GATA! AI SCAPAT DE SPANZURATOARE!</h1><h3>S-au epuizat intrebarile iar tu ai avut " . $_SESSION['nr_intr'] . " greseli</h3>";
                $_SESSION['buton'] = '<input type= "submit" name="submit" value="Incepe">';
            
            }elseif($checked_anterior == $_SESSION['check'] && $_SESSION['numar'] >0){
                $greseli = 4 - $_SESSION['nr_intr'];
                $mesaj = "<h2 style='color: green;'>Ai raspuns CORECT!</h2><h3>Ai dreptul la $greseli greseli</h3>";
                $nr = &$_SESSION['nr_intr'];
            }elseif($checked_anterior != $_SESSION['check'] && $_SESSION['numar'] >0){
                $greseli = 4 - $_SESSION['nr_intr'];
                $mesaj = "<h2 style='color: red;'>Ai raspuns INCORECT si te apropii de moarte!!</h2><h3>Ai dreptul la $greseli greseli</h3>";
                //$_SESSION['nr_intr']++;
                $nr = &$_SESSION['nr_intr'];
                $nr++;
            }     
        //afisare mesaje si decizii
        echo "<div id='mesaj'>" . $mesaj . "</div>";
            
        if($_POST['submit']=="Raspunde" || $_POST['submit']=="Incepe"){
             
            if($_SESSION['numar'] < $nr_intr && $mesaj != "<h1 style='color: red;'>GATA! AI FOST SPANZURAT!</h1>") {
                echo"<h3>Trebuie sa raspundeti la urmatoarele intrebari:</h3><h3>" .($_SESSION['numar']+1).") ". $intrebari[$_SESSION['numar']]."</h3>";
            
                //extragem in array raspunsurile de la o intrebare       
                $raspunsuri = explode("*", $total_raspunsuri[$_SESSION['numar']]);
                
                //extragem in array campurile bifate de la raspunsuri
                $checked= explode("*", $total_check[$_SESSION['numar']]);
            
                foreach ($raspunsuri as $key => $value ) {
                        echo (($key+1 ).") ". $value);?> 
                        <label class="container">
                            <input type="checkbox" name="check[<?php echo $key ;?>]" >
                            <span class="checkmark"></span>
                        </label><br><br>
                <?php }
            }  
            //incrementam contorul intrebarilor
            $_SESSION['numar']++;
           
        }
       
            echo  $_SESSION['buton'];

        ?>
    
    </form><br><br>
</div>

<?php
if(!isset($nr)){
            $nr =0;
        } 
//output svg pentru spanzuratoare
echo <<<DESEN
<div id = "desen">
<img src = "img/$nr.svg">
</div>
DESEN;
?>
