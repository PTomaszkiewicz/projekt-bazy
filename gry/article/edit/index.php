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
if (isset($_POST["nazwa"], $_POST["autor"], $_POST["wydanie"], $_POST["opis"], $_FILES['foto'])){
    $uploadDirectory = "/var/www/html/22.04/galeria/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['jpeg','jpg','png', 'bmp', 'gif', 'pjpeg', 'svg+xml', 'tiff', 'webp', 'x-icon']; // These will be the only file extensions allowed 
    $sql="select id, nazwa, autor, data_wydania, opis, foto from gry where nazwa='".$_GET["gra"]."'";
    $result = $conn->query($sql);

    $fileName = $_FILES['foto']['name'];
    if (isset($fileName)){  
    $fileSize = $_FILES['foto']['size'];
    $fileTmpName  = $_FILES['foto']['tmp_name'];
    $fileType = $_FILES['foto']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $top=$row["id"];
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
   <h1>Edytuj grę: <?php echo $_GET["gra"];?></h1>
   <div class='article'>
   <?php
if (isset($_POST["nazwa"], $_POST["autor"], $_POST["wydanie"], $_POST["opis"], $fileName)){
    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
          $sql2="update gry set nazwa='".$_POST["nazwa"]."', autor='".$_POST["autor"]."', opis='".$_POST["opis"]."', foto='/22.04/galeria/$nazwapliku', data_wydania='".$_POST["wydanie"]."' where nazwa='".$_GET["gra"]."'";
          if ($conn->multi_query($sql2) === TRUE) {
            echo "Zaktualizowano artukuł\n";
          } else {
            echo "Wystąpił błąd";
          }
          
        } else {
          echo "Wystąpił błąd z plikiem.";
        }
      } else {
        foreach ($errors as $error) {
          echo $error . "\nThese are the errors" . "\n";
        }
      }
    } else if (isset($_POST["nazwa"], $_POST["autor"], $_POST["wydanie"], $_POST["opis"]) and !isset($fileName)) {
        $sql4="update gry set nazwa='".$_POST["nazwa"]."', autor='".$_POST["autor"]."', opis='".$_POST["opis"]."', data_wydania='".$_POST["wydanie"]."' where nazwa='".$_GET["gra"]."'";
          if ($conn->multi_query($sql4) === TRUE) {
            echo "Zaktualizowano artukuł\n";
          } else {
            echo "Wystąpił błąd";
          }
    }
    $sql3="select nazwa, autor, data_wydania, opis, foto from gry where nazwa='".$_GET["gra"]."'";
$result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
        while($row3 = $result3->fetch_assoc()) {
   if($_SESSION["userrole"]=="admin"){
        echo "<form method='post' action='/22.04/gry/article/edit/?gra=".$_GET["gra"]."' enctype='multipart/form-data'>
        <label for='nazwa'>Nazwa:</label>
        <br>
        <input id='nazwa' minlenght='1' maxlenght='60'name='nazwa' value='".$row3["nazwa"]."' required>
        <br>
        <label for='autor'>Autor:</label>
        <br>
        <input id='autor' minlenght='1' maxlenght='60' name='autor'  value='".$row3["autor"]."' required>
        <br>
        <label for='wydanie'>Data wydania:</label>
        <br>
        <input id='wydanie' name='wydanie' type='date' value='".$row3["data_wydania"]."' required>
        <br>
        <img class='edit-foto'src='".$row3["foto"]."' placeholder='Brak zdjęcia'>
        <br>
        <lable for='foto'>Zmień zdjęcie:</label>
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
        image/jpg'>
        <br>
        <label for='opis'>Opis:</label>
        <br>
        <textarea name='opis' id='opis' minlenght='1' maxlenght='5000' required>".$row3["opis"]."</textarea>
        <br>
        <input type='submit' name='submit' value='Zapisz'>
        </form>";
   } else {
       echo "Zaloguj się jako administrator, aby edytować artykuł";
   }
}
    } else {
        echo "Nie ma takiej gry";
    }
   ?>
   </div>
</body>
</html>