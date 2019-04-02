<?php
session_start();

$useri = array('gabi'=>'pass', 'ionut'=>'pass', 'user'=>'pass');

$form = <<<GATA
<form method=post action="$_SERVER[PHP_SELF]">
<input type=text name=user placeholder="user"/><br />
<input type=password name=pass placeholder="pass"/><br />
<input type=submit value=Login profesor />
</form>
GATA;


# daca s-a solicitat logout, desfiintam sesiunea si toate cookie-urile 
if (isset($_GET['logout'])) {
    setcookie(session_name(), "", time()-10000);
    session_destroy();
    setcookie('nr', "", time()-100000);
    setcookie('tries', "", time()-100000);
    $msg = "You are now logged out.";
    $login_link = true;
} else {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $show_form = true;
    } else {
        if (empty($_POST['user']) || empty($_POST['pass'])) {
            $msg = "Introduceti user si parola!";
            $show_form = true;
        } else {
            # verificam daca userul exista in array si daca parola corespunde
           if (!array_key_exists($_POST['user'], $useri) || $useri[$_POST['user']] !== $_POST['pass']) {
                $msg = "Autentificare esuata!";
                $show_form = true;
            } else {
                # salvam username-ul in sesiune, ca indicatie a faptului ca userul s-a autentificat corect
                $_SESSION['username'] = $_POST['user'];
                header('Location: intrebari.php');
                echo"Esti logat ca ". $_SESSION['username'];
                //exit;
            }
        }
    }
}

?>

<!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css" />

  </head>
  <body>
      <header>

        <?php
        if (!empty($msg)) {
            echo "<div class='msg'>$msg</div>";
        }
        if (!empty($show_form)) {
            echo $form;
        }
        
        if (!empty($login_link)) {
            echo "<div style='text-align: center'><a href='$_SERVER[PHP_SELF]' >Log in again</a></div>";
        }
        ?>
    </header>
</body>
</html>
