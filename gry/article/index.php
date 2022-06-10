<?php session_start();
?>
<?php
include '/22.04/session.php';
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
$gra=$_GET["gra"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
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
$sql5="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where gry.nazwa='$gra' and oceny.id_user='".$_SESSION["userid"]."'";
$result5 = $conn->query($sql5);
$sql = "SELECT nazwa, foto, autor, opis, data_wydania, `data` FROM gry where nazwa='$gra'";
$result = $conn->query($sql);
$sql2="select oceny.id as 'mainid', oceny.id_gry as 'idgra', oceny.id_user as 'iduser', oceny.ocena as 'ocenagry', oceny.opis as 'opisgry', oceny.data as 'dataoceny', gry.id as 'idgry2', gry.nazwa as 'gra', users.id as 'iduser2', users.name as 'user'
from oceny inner join gry on oceny.id_gry=gry.id 
inner join users on oceny.id_user=users.id
where gry.nazwa='$gra' group by mainid order by dataoceny desc limit 5";
$result2 = $conn->query($sql2);
$sql3="select avg(oceny.ocena) as 'srednia' from oceny inner join gry on oceny.id_gry=gry.id where gry.nazwa='$gra'";
$result3 = $conn->query($sql3);
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
    <?php
    if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo 
    "<h1>".$row["nazwa"]."</h1>
    <div class='article'>
        <div class='article-top'>
            <img src='".$row["foto"]."'/>
            <div class='article-data'>
                <p>Średnia ocen: ";if ($result3->num_rows > 0) { 
                  while($row3 = $result3->fetch_assoc()) {
                    echo round($row3["srednia"])."/10";
                  }
                }
                  echo "</p>
                <p>Autor: ".$row["autor"]."</p>
                <p>Data wydania: ".$row["data_wydania"]."</p>
            </div>
        </div>
        <p class='article-opis'>".$row["opis"]."</p>
        <small>".$row["data"]."</small>";
        if ($_SESSION["userrole"]=="admin"){
          echo "<br><a href='/22.04/gry/article/edit/?gra=$gra'><button>Edytuj</button></a>
          <form method='post' action='/22.04/gry/deletion/?gra=".$_GET["gra"]."'>
          <input type='submit' value='Usuń' name='del'>
          </form>";
        }
    echo "</div>
    ";
  }
} else {
  echo "<p style='text-align: center; width:100%; margin:auto;'>Nie ma takiej gry</p>";
}
echo "<div class='last-reviews'>";
echo "<h2>Ostatnie oceny</h2>";
if ($result2->num_rows > 0) {
  while($row2 = $result2->fetch_assoc()) {
    echo "<p class='review-user'>".$row2["user"]."</p>
    <p>Ocena: ".$row2["ocenagry"]."/10</p>
    <p>".$row2["opisgry"]."</p>
    <small>".$row2["dataoceny"]."</small>";
    
  }
  echo "
  <br>
<div class='all-reviews-div' >
<a href='/22.04/gry/reviews/?gra=$gra'>
<button class='all-reviews-butt' >Pokaz wszytskie oceny</button>
</a>
</div>";
} else {
  echo "Nie ma jeszcze żadnych ocen";
}

echo "<h2>Twoja ocena</h2>";
if ($_SESSION["userid"]!==null){
if ($result5->num_rows > 0) {
    while($row5 = $result5->fetch_assoc()) {
      echo "<form method='post' action='/22.04/gry/article/?gra=$gra&action=edit'>
      <p class='review-user'>".$row5["user"]."</p>
      <p>Ocena: <input name='ocenagry'type='number' min='1' max='10'value='".$row5["ocenagry"]."' required>/10</p>
      <textarea name='opisgry' maxlenght='1000' minlenght='3' required>".$row5["opisgry"]."</textarea>
      <br>
      <small>".$row5["dataoceny"]."</small>
      <br>";
      echo "<input type='submit' class='edit-review-butt' value='Zapisz'><input type='button' onclick='window.location.href=\"/22.04/gry/article/?gra=$gra&action=delete\"' class='edit-review-butt' value='Usuń'></form>";
    }
  } else {
    echo "<form method='post' action='/22.04/gry/article/?gra=$gra&action=add'>
    <p class='review-user'>".$row5["user"]."</p>
    <p>Ocena: <input name='ocenagry'type='number' min='1' max='10' required>/10</p>
    <textarea name='opisgry' maxlenght='1000' minlenght='3' required></textarea>
    <br>";
    echo "<input type='submit' class='edit-review-butt' value='Udostępnij'></form>";
  }
} else{
  echo "<p>Zaloguj się, aby dodać opinię</p>";
}
?>

</div>
</body>
</html>