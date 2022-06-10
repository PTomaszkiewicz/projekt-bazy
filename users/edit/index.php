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
    <h1>ID <?php echo $_GET["id"];?></h1>
      <?php
        if ($_SESSION["userrole"]=="admin"){
            
            $nazwa=$_POST["name"];
            $passwd=$_POST["passwd"];
            $mail=$_POST["mail"];
            $role=$_POST["role"];
            if(isset($nazwa, $passwd, $mail, $role)){
                $sql3 = "UPDATE users SET name='".$nazwa."', password='".$passwd."', mail='".$mail."', role='".$role."' WHERE id='".$_GET["id"]."'";
                if ($conn->query($sql3) === TRUE) {
                    echo "<p style='text-align: center; width:100%; margin:auto;'>Zaktualizowano zmiany</p>";
                  } else {
                    echo "<p style='text-align: center; width:100%; margin:auto;'>Wystąpił błąd: " . $conn->error."</p>";
                  }
            }
            $sql="select id, name, mail, password, role, register_date from users where id='".$_GET["id"]."'";
            $result = $conn->query($sql);
            $sql2="select role from users group by role";
            $result2 = $conn->query($sql2);
          if ($result->num_rows > 0) {    
              while($row = $result->fetch_assoc()) {
               echo "<form class='useredit' method='post' action='/22.04/users/edit/?id=".$_GET["id"]."'>
               <label for='name'>Nazwa:</label>
               <br>
               <input id='name' name='name' value='".$row["name"]."'>
               <br>
               <label for='halso'>Hasło:</label>
               <br>
               <input type='password' name='passwd' onmouseover='showPasswd(this)' id='haslo' onmouseout='hidePasswd(this)'value='".$row["password"]."'>
               <br>
               <lable for='mail'>E-mail:</label>
               <br>
               <input id='mail' name='mail' type='email' value='".$row["mail"]."'>
               <br>
               <label for='role'>Rola</label>
               <br>
               <select name='role' id='role' >";
               if ($result2->num_rows > 0) {
                while($row2 = $result2->fetch_assoc()) {
                    echo "<option ";
                    if($row["role"]==$row2["role"]){
                        echo "selected='selected'";
                    }
                    echo "value='".$row2["role"]."'>".$row2["role"]."</option>";
                }
               }
               echo "</select>
               <br>
               <label for='date'>Data rejestracji:</label>
               <br>
               <input type='date' value='".$row["register_date"]."' readonly>
               <br>
               <input type='submit' value='Zapisz zmiany'>
               </form>";
              }
            } else {
              echo "Błąd, nieznaleziono użytkownika";
            }
        } else{
            echo "<h1>Zaloguj się jako administrator, aby zarządzać użytkownikami</h1>";
        }
      ?>
</body>
</html>