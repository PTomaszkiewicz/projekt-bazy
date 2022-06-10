<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$sql = "SELECT nazwa, foto FROM gry order by nazwa asc";
$result = $conn->query($sql);
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
    <h1>Gry</h1>
    <div class='search-game'>
      <?php
      if ($_SESSION['userrole']=="admin"){
        echo "<a href='/22.04/gry/dodaj'><button>Dodaj</button></a>";
      } 
      ?>
      <h2>Znajdź grę: </h2>
      <form class='inflex' method='get' action='/22.04/gry/szukaj'>
        <input name='name'/>
        <input class='search-butt'type='submit' value='Szukaj'/>
      </form>
    </div>
    <table>
      <?php
          if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                echo "<tr><td class='gryfoto'><a href='/22.04/gry/article?gra=".$row["nazwa"]."'><img src='".$row["foto"]."' /></a></td><td><h2><a class='gry-title' href='/22.04/gry/article?gra=".$row["nazwa"]."'>".$row["nazwa"]."</a></h2></td>";
              }
            } else {
              echo "<p style='text-align: center; width:100%; margin:auto;'>Brak gier</p>";
            }
      ?>
    </table>
</body>
</html>