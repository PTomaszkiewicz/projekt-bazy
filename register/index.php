<?php session_start();
?>
<?php
$servername = "localhost";
$username = "susy";
$password = "novell";
$db = "projekt-bazy";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$nazwa=$_POST["nazwa"];
$mail=$_POST["mail"];
$haslo=$_POST["haslo"];
$sql3="INSERT into users (name, mail, password) values('$nazwa', '$mail', '$haslo')";
$sql = "SELECT `name` FROM users where `name`='$nazwa'";
$sql2 = "SELECT mail FROM users where mail='$mail_'";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$message;
if (isset($nazwa) and isset($mail)){
    if ($result->num_rows > 0) {
                $message="Ten nick jest zajęty";
    }
    else if ($result2->num_rows > 0) {
                $message="Ten e-mail jest zajęty";
    }
    else{
        if ($conn->query($sql3) === TRUE) {
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."success");
          } else {
            $message="Wystąpił błąd spróbuj później";
          }
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
        <h1>Rejestracja</h1>
        <div class='register-bottom'>
        <form method='post' action='/22.04/register/'>
            <label for='nazwa'>Nazwa użytkownika</label>
            </br>
            <input class='register-textin'name='nazwa' id="nazwa" maxlength="30" minlength="4" required/>
            <br>
            <label class='register-textin'for='mail'>E-mail</label>
            <br>
            <input class='register-textin'name='mail'id='mail' type='email' maxlength="60" minlength="3" required/>
            <br>
            <label class='register-textin'for='haslo'>Hasło</label>
            <br>
            <input class='register-textin haslo'name='haslo' id='haslo'onchange='validatePassword()'type='password' maxlength="30" minlength="4" required/>
            <br>
            <label for="phaslo">Powtórz hasło</label>
            <br>
            <input class='register-textin'type='password' onkeyup="validatePassword()" id='phaslo' maxlength="30" minlength="4" required/>
            <br>
            <div class='register-regu'>
                <input class='register-check'name='regulamin' id="regulamin" type='checkbox' required/>
                <label for='regulamin'>Akceptuje regulamin serwisu</label>
            </div>
            <p class='register-message'><?php echo $message; ?></p>
            <br>
            <input type='submit' class='reg-submit' value='Zarejestruj'>
        </form>
        </div>
    </div>
    <script>var password = document.getElementById("haslo");
        var confirm_password = document.getElementById("phaslo");
        var str,
        element = document.getElementById('phaslo');
        if (element != null) {
            str = element.value;
        }
        else {
            str = null;
            console.log(str)
        }
        function validatePassword(){
          if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Hasła nie pasują");
          } else {
            confirm_password.setCustomValidity('');
          }
        }
</script>
</body>
</html>
