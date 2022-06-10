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
if (isset($_POST["nazwa"], $_POST["autor"], $_POST["wydanie"], $_POST["opis"])){
    $uploadDirectory = "/var/www/html/22.04/galeria/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['jpeg','jpg','png', 'bmp', 'gif', 'pjpeg', 'svg+xml', 'tiff', 'webp', 'x-icon']; // These will be the only file extensions allowed 
    $sql='select id from gry order by id desc limit 1';
    $result = $conn->query($sql);

    $fileName = $_FILES['foto']['name'];
    $fileSize = $_FILES['foto']['size'];
    $fileTmpName  = $_FILES['foto']['tmp_name'];
    $fileType = $_FILES['foto']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $top=$row["id"]+1;
            $nazwapliku=$top."logo.".$fileExtension;
        }
    }
    $uploadPath = $uploadDirectory . basename($nazwapliku); 

    if (isset($_POST['submit'])) {

      if (! in_array($fileExtension,$fileExtensionsAllowed)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
      }

      if ($fileSize > 4000000) {
        $errors[] = "File exceeds maximum size (4MB)";
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
   <h1>Dodaj gre</h1>
   <div class='article'>
   <?php
if (isset($_POST["nazwa"], $_POST["autor"], $_POST["wydanie"], $_POST["opis"])){
    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
          $sql2="insert into gry (nazwa, autor, opis, foto, data_wydania) 
          values ('".$_POST["nazwa"]."', '".$_POST["autor"]."', '".$_POST["opis"]."', '/22.04/galeria/$nazwapliku', '".$_POST["wydanie"]."')";
          if ($conn->multi_query($sql2) === TRUE) {
            echo "Dodano grę";
          } else {
            echo "Wystąpił błąd";
          }
          
        } else {
          echo "Wystąpił błąd z plikiem.";
        }
      } else {
        foreach ($errors as $error) {
          echo $error . "These are the errors" . "\n";
        }
      }
    }
   if($_SESSION["userrole"]=="admin"){
        echo "<form method='post' action='/22.04/gry/dodaj/' enctype='multipart/form-data'>
        <label for='nazwa'>Nazwa:</label>
        <br>
        <input id='nazwa' minlenght='1' maxlenght='60'name='nazwa' required>
        <br>
        <label for='autor'>Autor:</label>
        <br>
        <input id='autor' minlenght='1' maxlenght='60' name='autor' required>
        <br>
        <label for='wydanie'>Data wydania:</label>
        <br>
        <input id='wydanie' name='wydanie' type='date' required>
        <br>
        <lable for='foto'>Zdjęcie:</label>
        <br>
        <input id='foto' name='foto' type='file' accept='image/apng,
        image/bmp,
        image/gif,
        image/jpeg,
        image/pjpeg,
        image/png,
        image/svg+xml,
        image/tiff,
        image/webp,
        image/x-icon,
        image/jpg'required>
        <br>
        <label for='opis'>Opis:</label>
        <br>
        <textarea name='opis' id='opis' minlenght='1' maxlenght='5000' required></textarea>
        <br>
        <input type='submit' name='submit' value='Dodaj'>
        </form>";
   } else {
       echo "Zaloguj się jako administrator, aby dodać artykuł";
   }
   ?>
   </div>
</body>
</html>