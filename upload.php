<?php

declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wilkowski</title>
</HEAD>

<BODY>
    <?php
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

    session_start();
    $title = $_POST['title']; // login z formularza
    $title = htmlentities($title, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $musician = $_POST['musician']; // login z formularza
    $musician = htmlentities($musician, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $lyrics = $_POST['lyrics']; // login z formularza
    $lyrics = htmlentities($lyrics, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $idmt = $_POST['idmt']; // login z formularza
    $idmt = htmlentities($idmt, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej

    if ($lyrics == "") $lyrics = "NONE";

    $_SESSION['z6title'] = $title;
    $_SESSION['z6musician'] = $musician;
    $_SESSION['z6lyrics'] = $lyrics;
    $_SESSION['z6idmt'] = $idmt;


    $usernameid = mysqli_query($link, "SELECT id FROM UsersLab5 where username=\"" . $_SESSION['username'] . "\";") or die("DB error: $dbname");
    $row = mysqli_fetch_array($usernameid);
    $idu = $row[0];

    $filename = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));

    $target_dir = "songs";
    $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists 
    if (file_exists($target_file)) {
        echo "Plik już istnieje.";
        $_SESSION['error'] = "Plik juz istnieje";
        $uploadOk = 0;
    }

    // Check file size 
    if ($_FILES["fileToUpload"]["size"] > (1048576 * 25)) {
        echo "Plik jest za duży.";
        $_SESSION['error'] = "Plik jest za duży.";
        $uploadOk = 0;
    }

    // Allow certain file formats 
    if ($imageFileType != "mp3" && $imageFileType != "wav") {
        echo "Tylko pliki mp3 oraz wav.";
        $_SESSION['error'] = "Tylko pliki mp3 oraz wav.";
        $uploadOk = 0;
    }


    // Check if $uploadOk is set to 0 by an error 
    if ($uploadOk == 0) {
        echo "Nie przesłano";
        header('Location: select.php');
        exit();
    } else // if everything is ok, try to upload file 
    {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            print "Plik " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " przesłano.";
            $_SESSION['error'] = "Plik " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " przesłano. "/*.$target_file*/;
            $result = mysqli_query($link, "INSERT INTO song (`title`, `musician` , `idu`, `filename`, `lyrics`, `idmt`) 
                VALUES ('$title', '$musician', $idu, '$filename' , '$lyrics', $idmt);") or die("DB error: $dbname");
            mysqli_close($link);
            $_SESSION['z6title'] = "";
            $_SESSION['z6musician'] = "";
            $_SESSION['z6lyrics'] = "";
            $_SESSION['z6idmt'] = "";
            header('Location: index2.php');
            exit();
        } else {
            echo "Doszło do błędu przy przesyłaniu.";
            $_SESSION['error'] = "Doszło do błędu przy przesyłaniu.";

            header('Location: select.php');
            exit();
        }
    }
    ?>
</BODY>

</HTML>