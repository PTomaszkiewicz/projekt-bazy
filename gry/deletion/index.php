<?php session_start();
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
$gra=$_GET["gra"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
if($_POST["del"]=="Usuń"){
    $sql="delete from gry where nazwa='".$_GET["gra"]."'";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/22.04/style.css">
    <title>Ratedex</title>
</head>
<body>
    <nav>
        <a href="/22.04/">Home</a>
        <a href="/22.04/gry">Gry</a>
        <?php
        if($_SESSION["userrole"]=="admin"){
           echo "<a href='/22.04/users/'>Użytkownicy</a>";
        }
        ?>
        <?php
        if(!isset($_SESSION["username"])){
           echo "<a href='/22.04/login/'>Zaloguj</a>";
           echo "<a href='/22.04/register'>Zarejestruj</a>";
        } else {
            echo "<a href='/22.04/logout/'>Wyloguj</a>";
            echo "<a>Ty: ".$_SESSION["username"]."</a>";
        }
        ?>
    </nav>
    <h1>Usuwanie artykułu</h1>
<?php
if ($_POST["del"]=="Usuń"){
if ($conn->query($sql) === TRUE) {
    echo "Udało się usunąć ".$_GET["gra"];
  } else {
    echo "Nie udało się usunąć ".$_get["gra"].":" . $conn->error;
  }
} else {
    if ($_SESSION["userrole"]=="admin"){
        echo "Nie udało się usunąć ".$_get["gra"];
    }else{
        echo "Zaloguj się jako administrator aby usuwać artykuły";
    }
}
?>
</body>
</html>