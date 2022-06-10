<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

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
    <script>
        function showPasswd(wybranyin){
            wybranyin.type='text';
        };
        function hidePasswd(wybranyin){
            wybranyin.type='password';
        };

    </script>
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
    <h1>Użytkownicy</h1>
    <div class='search-game'>
      <h2>Znajdź użytkownika: </h2>
      <form class='inflex' method='get' action='/22.04/users/szukaj'>
        <input name='search'/>
        <input class='search-butt'type='submit' value='Szukaj'/>
      </form>
    </div>
      <?php
        if ($_SESSION["userrole"]=="admin"){
            $todelete=$_POST["todelete"];
            
            
            if (isset($todelete)){
                $deleteam=count($todelete);
                foreach($todelete as $todelete){
                    $sql10="DELETE from oceny WHERE id_user='$todelete'";
                    $sql2="DELETE from users WHERE id='$todelete'";
                    $conn->query($sql10);
                    if ($conn->query($sql2) === TRUE) {
                        
                    } else {
                        echo "<p style='text-align: center; width:100%; margin:auto;'>Wystąpił błąd: " . $conn->error."</p>";
                    }
                }
                echo "<p style='text-align: center; width:100%; margin:auto;'>Usunięto $deleteam użytkowników</p>";
            }
            $sql="select id, name, mail, password, role, register_date from users";
            $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              // output data of each row
                echo "<table style='userstable'>
                <form method='post' action='/22.04/users/'>
                <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Hasło</th>
                <th>E-mail</th>
                <th>Rola</th>
                <th>Data rejestracji</th>
                <th>Edytuj</th>
                <th><button type='submit'>Usuń</button></th>
                </tr>";
              while($row = $result->fetch_assoc()) {
               echo "<tr><td>".$row["id"]."</td>
               <td>".$row["name"]."</td>
               <td><input type='password' onmouseover='showPasswd(this)' onmouseout='hidePasswd(this)'value='".$row["password"]."' readonly></td>
               <td>".$row["mail"]."</td>
               <td>".$row["role"]."</td>
               <td>".$row["register_date"]."</td>
               <td><input type='button' onclick='window.location.href=\"/22.04/users/edit/?id=".$row["id"]."\"' value='Edytuj'></td>
               <td><input type='checkbox' name='todelete[]' value='".$row["id"]."'></td></tr>";
              }
             echo "</form></table>";
            } else {
              echo "<p style='text-align: center; width:100%; margin:auto;'>Błąd, nieznaleziono użytkowników</p>";
            }
        } else{
            echo "<h1>Zaloguj się jako administrator, aby zarządzać użytkownikami</h1>";
        }
      ?>
</body>
</html>