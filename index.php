<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Wilkowski</title>
</head>
<BODY>
    <?php
        session_start();
        echo "Zaloguj się";
        echo "<p style=\"color:red;font-size:15px;\">".$_SESSION['error']."</p>";
        echo "<form method=\"post\" action=\"weryfikuj.php\">";
        echo "Login:<br><input type=\"text\" name=\"user\" maxlength=\"20\" size=\"20\"><br>";
        echo "Hasło:<br><input type=\"password\" name=\"pass\" maxlength=\"20\" size=\"20\"><br>";
        echo "<input type=\"submit\" value=\"Send\"/>";
        echo "</form>";
        echo "<br><a href=\"rejestruj.php\">...lub załóż konto!</a>";
        $_SESSION ['error']="";
    ?>
</BODY>
</HTML>