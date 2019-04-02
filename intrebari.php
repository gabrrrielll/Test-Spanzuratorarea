<?php
session_start();

if (empty($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
?>
<form method='POST' action="<?php echo $PHP_SELF; ?>">
    

 <link rel="stylesheet" href="style.css">
 <header>
     <?php echo"Esti logat ca ". $_SESSION['username'];?>
     <input type='submit' name="submit" value='Logout'>
 </header>

<!---deschidem formularul de completat intrebari cu metoda POST--->

    <?php   
    
    // 1.a) extragem informatia din fisierul de intrebari
    $f_intrebari = file("intrebari.txt");
    // 1.b) extragem informatia din fisierul de raspunsuri
    $f_raspunsuri = file("raspunsuri.txt");
    // 1.c) extragem informatia din fisierul de bife
    $f_check = file("checkbox.txt");

    //extrage valorile raspunsurilor din fisier
    $total_raspunsuri = explode("|",$f_raspunsuri[0]);
    
   
    //extrage valorile campurilor cu check din fisier
    $total_check = explode("|",$f_check[0]);

if($_POST['submit']=="Logout"){
    $_SESSION['username'] = "";
    echo "<meta http-equiv=refresh content=\"0; URL=http://expertcontabil.ro/test\">";
}

        // 1.a) Stabilim ce se afiseaza in campurile cu intrebari 
        if(isset($_POST['intrebare'])){
            
            //completaza campurile cu valoarea din POST
            global $intrebari;
            $intrebari = $_POST['intrebare'];
            
           //daca nu s-au trimis inca intrebari 
        }  else {
            
            //extrage valorile intrebarilor din fisier
            $intrebari = explode("|",$f_intrebari[0]);
            
            //incarca contorul numarului de intrebari cu nr de intrebari existent deja in fisier
            $_SESSION['nr'] = count(explode("|",$f_intrebari[0]));
           
        }
    
        // 2.a) Pentru ADAUGARE intrebare
        if( $_POST['submit']=="Adauga intrebare"){
            
            //construim o variablila de sesiune (ptr.persistenta) si o incrementam la fiecare submit    
            $_SESSION['nr']++;
        }
      
        // 3.a) daca vrem sa stergem ultima intrebare verificam daca s-a facut submit
        if( $_POST['submit']=="Sterge ultima intrebare"){
            
            //scadem valoarea contorului cu 1
            $_SESSION['nr']=$_SESSION['nr']-1;
            
            //eliminam ultima valoare din array cu ajutorul unei variabile anonime
            $x =  array_pop($intrebari); 
            
            //construieste variabila $insert_intrebari cu toate intrebarile
            $insert_intrebari = implode("|",  $_POST['intrebare']);
         
            //si il rescriem in fisier
            file_put_contents("intrebari.txt", $insert_intrebari); 
        }
        
// 4. daca vrem sa modificam continutul verificam daca s-a facut submit in acest sens
if( $_POST['submit']=="Actualizeaza"){
    
    //actualizam variabila $insert_intrebari cu valoarea noilor campuri
    $insert_intrebari = implode("|",  $_POST['intrebare']);
 
    foreach($intrebari as $nr => $q) {
        echo("|nr INTREBARE:".$nr." -->".$q."<br>");
        
            	
    $raspunsuri= explode("*", $total_raspunsuri[$nr]);
    $checked= explode("*", $total_check[$nr]); print_r($checked);
    
        if(isset($raspunsuri)){
            foreach($raspunsuri as $nr_r => $w) {
                    echo("|nr raspuns:".$nr_r." -->".$w."|valoare bifa:".$nr_r." -->".$_SESSION['check'][$nr_r]."<br>");
                
                        if($_POST['checkbox'][$nr][$nr_r] == 'on'){ 
                            $_SESSION['check'][$nr_r] = 'on';
                        }else{
                            $_SESSION['check'][$nr_r] = 'off';
                        } 
                
            }
        }
      
        //facem implode cu toate raspunsurile de la o intrebare cu despartitorul "*"    
        if($_POST['raspuns']){
            $x[]=implode("*", $_POST['raspuns'][$nr]);
        
            //apoi implode cu raspunsurile din fiecare intrebare separat cu despartitorul "|"
            $insert_raspunsuri=implode("|", $x);
        
        }
        //facem implode cu toate bifarile raspunsurilor de la o intrebare cu despartitorul "*"
        if (isset($_SESSION['check'])){
            $y[]=implode("*", $_SESSION['check']);    
        
            //apoi implode cu bifarile la raspunsurile din fiecare intrebare separat cu despartitorul "|"
            $insert_cheched = implode("|", $y); 
        }
    }

    //si le introducem in fisier 
    file_put_contents("intrebari.txt", $insert_intrebari); 
    file_put_contents("raspunsuri.txt", $insert_raspunsuri);print_r($insert_raspunsuri);
    file_put_contents("checkbox.txt", $insert_cheched);

    //reincarcam pagina pentru accesarea valorilor dupa submitul pe care l-am trimis
     echo "<meta http-equiv=refresh content=\"0; URL=http://expertcontabil.ro/test/intrebari.php\">";
}
       if(!isset($_SESSION['nr'])){
            $_SESSION['nr'] = count(explode("|",$f_intrebari[0]));
        }

for($nr=0; $nr<$_SESSION['nr']; $nr++){ ?>
   
    		Modifica intrebarea cu numarul:  <?php echo ($nr+1) ;?> <br>
    		&nbsp;&nbsp;&nbsp; <textarea class="blue" name="intrebare[]" ><?php echo $intrebari[$nr] ;?></textarea><br>
    		Adauga variante de raspuns si bifeaza raspunsul corect: <br>
    		
    	<?php 
    	
$raspunsuri= explode("*", $total_raspunsuri[$nr]);
$checked= explode("*", $total_check[$nr]);

for($nr_r = 0; $nr_r < 4; $nr_r++){ 
 
    echo ($nr_r+1) ;?>) <textarea class="yellow" name="raspuns[<?php echo $nr ;?>][<?php echo $nr_r ;?>]" ><?php echo ($raspunsuri[$nr_r] );?></textarea>
    	 <label class="container">
            <input type="checkbox" name="checkbox[<?php echo $nr ;?>][<?php echo $nr_r ;?>]"   <?php if($checked[$nr_r] == 'on'){ echo "checked";}?> >
            <span class="checkmark"></span>
        </label><br>
        
<?php }?>
    
 <br><br>   
    <?php  }?>
            <input type='submit' name="submit" value='Adauga intrebare'>
           	<input type='submit' name="submit" value='Sterge ultima intrebare'>
    		<input type='submit' name="submit" value='Actualizeaza'>
</form>
	
