<?php

declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wilkowski</title>
    </script>
</HEAD>

<BODY>
    <?php
    session_start();
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$connection) {
        echo " MySQL Connection error." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
    if (!$_SESSION['loggedin'] == "true") {
        $_SESSION['error'] = "Nie zalogowano";
        header('Location: index.php');
        exit();
    }
    echo "<a href=\"logout.php\">Wyloguj</a>";
    print "<br>Witaj: " . $_SESSION['username'];
    print "<hr>";
    ?>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <?php echo "<p style=\"color:red;font-size:15px;\">" . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = ""; ?>
        Dodaj utwór<br><input type="file" name="fileToUpload" id="fileToUpload"><br><br>
        <label for="title">Tytuł:</label><br>
        <input type="text" name="title" required><br>
        <label for="musician">Autor:</label><br>
        <input type="text" name="musician" required><br>
        <label for="lyrics">Słowa:</label><br>
        <input type="text" name="lyrics"><br>
        <label for="idmt">Rodzaj muzyki:</label><br>
        <select name="idmt">
            <option value="1">Rap</option>
            <option value="2">Rock</option>
            <option value="3">Pop</option>
            <option value="4">Electronic dance</option>
            <option value="5">R&B</option>
            <option value="6">Live</option>
            <option value="7">Techno</option>
            <option value="8">Metal</option>
            <option value="9">Jazz</option>
            <option value="10">Classic</option>
            <option value="11" selected>Other</option>
        </select>

        <input type="submit" value="Upload" name="submit">
    </form>
    <BR>
    <hr>







    <a href="playlists.php">Utwórz playliste / Dodaj utwór do playlisty</a><br>

    <?php
    print "<br><br>Wybierz playliste";
    $userid = $_SESSION['userid'];
    print "<br><form method=\"post\" action=\"playpl.php\">
    <select name=\"idpl\">";
    print "$userid";
    $playlists = mysqli_query($connection, "Select * from playlistname where idu=$userid ORDER BY name ASC;") or die("DB error: $dbname 1");
    $playlists2 = mysqli_query($connection, "Select * from playlistname where idu!=$userid AND public=1 ORDER BY name ASC;") or die("DB error: $dbname 2");
    while ($row = mysqli_fetch_array($playlists)) {
        $idpl = $row[0];
        $plname = $row[2];
        print "<option value=\"$idpl\">$plname</option>";
    }
    while ($row = mysqli_fetch_array($playlists2)) {
        $idpl = $row[0];
        $plname = $row[2];
        print "<option value=\"$idpl\">$plname</option>";
    }

    print "</select>

    <input type=\"submit\" value=\"Odtwórz\"/>
    </form>";
    ?>
    <?php echo "<p style=\"color:red;font-size:15px;\">" . $_SESSION['error'] . "</p>";
    $_SESSION['error'] = ""; ?>
    <?php
    print "<hr>";
    $result = mysqli_query($connection, "Select * from song ORDER BY datetime DESC") or die("DB error: $dbname");
    print "Wszystkie utwory<br>";
    print "<TABLE CELLPADDING=5 BORDER=1>";

    print "<TR><TD>Tytuł</TD><TD>Autor</TD><TD>Utwór</TD></TR>\n";
    while ($row = mysqli_fetch_array($result)) {
        $ids = $row[0];
        $title = $row[1];
        $musician = $row[2];
        $datetime = $row[3];
        $idu = $row[4];
        $filename = $row[5];
        $lyrics = $row[6];
        $idmt = $row[7];
        print "<TR>";
        print "<TD>$title</TD>";
        print "<TD>$musician</TD>";
        print "<TD>";
        print "<audio id=\"audio\" controls>
                    <source src=\"songs/$filename\">
                        Your browser does not support the audio element.
                </audio>";
        print "</TD>";
        print "</TR>\n";
    }
    print "</TABLE>";
    mysqli_close($connection);
    ?>
    <script>
        var aud = document.getElementById("audio");
        aud.volume = 0.2;
    </script>
</BODY>

</HTML>