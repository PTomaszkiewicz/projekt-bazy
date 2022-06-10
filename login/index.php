<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$login_name=$_POST["nazwa"];
$login_password=$_POST["haslo"];
$sql = "SELECT name, password from users where name='$login_name' and password='$login_password'";
$result = $conn->query($sql);
$message;
if (isset($login_name) and isset($login_password)){
    if ($result->num_rows > 0) {
        $sql2="select id, name, role from users where name='$login_name'";
        $result2 = $conn->query($sql2);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $_SESSION["userid"]=$row["id"];
                $_SESSION["username"]=$row["name"];
                $_SESSION["userrole"]=$row["role"];
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."success");
            }
        } else {
            $message="Wystąpił błąd spróbuj później";
        }
        
    } else{
        $message="Podano błędne dane";
    }
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
    <div class='register-box'>
        <h1>Logowanie</h1>
        <div class='register-bottom'>
        <form method='post' action='/22.04/login/'>
            <label for='nazwa'>Nazwa użytkownika</label>
            </br>
            <input class='register-textin'name='nazwa' id="nazwa" maxlength="30" minlength="4" required/>
            <br>
            <label class='register-textin'for='haslo'>Hasło</label>
            <br>
            <input class='register-textin haslo'name='haslo' id='haslo'type='password' maxlength="30" minlength="4" required/>
            <p class='register-message'><?php echo $message; ?></p>
            <br>
            <input type='submit' class='reg-submit' value='Zaloguj'>
        </form>
        </div>
    </div>
</body>
</html>
