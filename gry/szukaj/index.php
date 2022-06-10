<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
// Create connection
$name=$_GET["name"];
$conn = mysqli_connect($servername, $username, $password, $db);
$sql = "SELECT nazwa, foto FROM gry where nazwa like '%$name%' order by nazwa asc";
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
    <?php echo "
        <h1>Wyniki wyszukiwania dla dla: $name</h1>
    ";?>
    <table>
      <?php
          if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                echo "<tr><td class='gryfoto'><a href='/22.04/gry/article?gra=".$row["nazwa"]."'><img src='".$row["foto"]."' /></a></td><td><h2><a class='gry-title' href='/22.04/gry/article?gra=".$row["nazwa"]."'>".$row["nazwa"]."</a></h2></td>";
              }
            } else {
                echo "<div class='search-game' ><h2>Brak wyników dla tego wyszukiwania. Spróbuj coś innego.</h2><hr><form class='inflex' method='get' action='/22.04/gry/szukaj'>
                <input name='name'/>
                <input class='search-butt' type='submit' value='Szukaj'/>
              </form></div>";
             
            }
      ?>
    </table>
</body>
</html>