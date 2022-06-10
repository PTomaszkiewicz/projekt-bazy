<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
$gra=$_GET["gra"];
// Create connection

$conn = mysqli_connect($servername, $username, $password, $db);
if($_POST["admin-action"]=="delete"){
  foreach($_POST["del-check"] as $deletion){
    $del="delete from oceny where id='".$deletion."'";
    $conn->query($del);
  }
}

$sql2="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where oceny.opis like'%".$_GET["sou"]."%' or users.name like '%".$_GET["sou"]."%' and gry.nazwa='$gra' group by mainid order by dataoceny desc";
$result2 = $conn->query($sql2);
// Check connection
//if (!$conn) {
// die("Connection failed: " . mysqli_connect_error());
//}
//echo "Connected successfully";
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
    <h1>Opinie dla <?php echo $gra;?></h1>
<?php
echo "<div class='last-reviews'>";
echo "<h2>Wyniki wyszukiwania dla: ".$_GET["sou"]."</h2>
<form class='review-search' method='get' action='/22.04/gry/reviews/szukaj/'>
<input style='display:none;' value='$gra' name='gra'>
<input placeholder='Opis lub użytkownik' name='sou'>
<input class='review-search-submit'type='submit' value='Szukaj'>
<br>
</form>";
if ($_SESSION["userrole"]=="admin"){
  echo "<form method='post' action='/22.04/gry/reviews/szukaj/?gra=$gra&sou=".$_GET["sou"]."'>
  <input style='display:none;' name='admin-action' value='delete'>
  <input type='submit' value='Usuń wybrane'>";
  
}
if ($result2->num_rows > 0) {
  while($row2 = $result2->fetch_assoc()) {
    echo "<p class='review-user'>".$row2["user"]."</p>
    <p>Ocena: ".$row2["ocenagry"]."/10</p>
    <p>".$row2["opisgry"]."</p>
    <small>".$row2["dataoceny"]."</small>";
    if ($_SESSION["userrole"]=="admin"){
      echo "<br>";
      echo "<label for='del-check'>Usuń</label><input type='checkbox' id='del-check' name='del-check[]' value='".$row2["mainid"]."'>";
    }
  }
} else {
  echo "<br>Nie ma takich ocen";
}
if ($_SESSION["userrole"]=="admin"){echo "</form>";}
?>

</div>
</body>
</html>