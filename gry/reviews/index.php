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
$sqlgra="select id from gry where nazwa='$gra'";
$resultgra = $conn->query($sqlgra);
$sql="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where gry.nazwa='$gra' and oceny.id_user='".$_SESSION["userid"]."'";
$result = $conn->query($sql);
if($_GET["action"]=="delete"){
    $sql3="delete from oceny where id_user='".$_SESSION["userid"]."'";
    $conn->query($sql3);
} else if($_GET["action"]=="edit"){
    $sql3="update oceny set ocena='".$_POST["ocenagry"]."', opis='".$_POST["opisgry"]."', data=current_timestamp() where id_user='".$_SESSION["userid"]."'";
    $conn->query($sql3);
} else if($_GET["action"]=="add"){
    if ($resultgra->num_rows > 0) {

        while($rowset = $resultgra->fetch_assoc()) {
            $graid=$rowset["id"];
        }
    }
    if ($result->num_rows < 1) {
        $sql3="insert into oceny (id_gry, id_user, ocena, opis) VALUES ('$graid', '".$_SESSION["userid"]."', '".$_POST["ocenagry"]."', '".$_POST["opisgry"]."')";
        $conn->query($sql3);
    }
}
$sql="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where gry.nazwa='$gra' and oceny.id_user='".$_SESSION["userid"]."'";
$result = $conn->query($sql);
$sql2="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where gry.nazwa='$gra' group by mainid order by dataoceny desc";
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
          echo "<a href='/22.04/users/'>U??ytkownicy</a>";
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
echo "<div class='last-reviews'>
<h2>Twoja ocena</h2>";
if ($_SESSION["userid"]!==null){
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "<form method='post' action='/22.04/gry/reviews/?gra=$gra&action=edit'>
      <p class='review-user'>".$row["user"]."</p>
      <p>Ocena: <input name='ocenagry'type='number' min='1' max='10'value='".$row["ocenagry"]."' required>/10</p>
      <textarea name='opisgry' maxlenght='1000' minlenght='3' required>".$row["opisgry"]."</textarea>
      <br>
      <small>".$row["dataoceny"]."</small>
      <br>";
      echo "<input type='submit' class='edit-review-butt' value='Zapisz'><input type='button' onclick='window.location.href=\"/22.04/gry/reviews/?gra=$gra&action=delete\"' class='edit-review-butt' value='Usu??'></form>";
    }
  } else {
    echo "<form method='post' action='/22.04/gry/reviews/?gra=$gra&action=add'>
    <p class='review-user'>".$row["user"]."</p>
    <p>Ocena: <input name='ocenagry'type='number' min='1' max='10' required>/10</p>
    <textarea name='opisgry' maxlenght='1000' minlenght='3' required></textarea>
    <br>";
    echo "<input type='submit' class='edit-review-butt' value='Udost??pnij'></form>";
  }
} else{
  echo "<p>Zaloguj si??, aby doda?? opini??</p>";
}
echo "<h2>Wyniki wyszukiwania dla: ".$_GET["sou"]."</h2>
<form class='review-search' method='get' action='/22.04/gry/reviews/szukaj/'>
<input style='display:none;' value='$gra' name='gra'>
<input placeholder='Opis lub u??ytkownik' name='sou'>
<input class='review-search-submit' type='submit' value='Szukaj'>
<br>
</form>";
if ($_SESSION["userrole"]=="admin"){
  echo "<form method='post' action='/22.04/gry/reviews/?gra=$gra'>
  <input style='display:none;' name='admin-action' value='delete'>
  <input type='submit' value='Usu?? wybrane'>";
  
}
if ($result2->num_rows > 0) {
  while($row2 = $result2->fetch_assoc()) {
    echo "<p class='review-user'>".$row2["user"]."</p>
    <p>Ocena: ".$row2["ocenagry"]."/10</p>
    <p>".$row2["opisgry"]."</p>
    <small>".$row2["dataoceny"]."</small>";
    if ($_SESSION["userrole"]=="admin"){
      echo "<br>";
      echo "<label for='del-check'>Usu??</label><input type='checkbox' id='del-check' name='del-check[]' value='".$row2["mainid"]."'>";
    }
  }
} else {
  echo "<br>Nie ma jeszcze ??adnych ocen";
}
if ($_SESSION["userrole"]=="admin"){echo "</form>";}
?>

</div>
</body>
</html>