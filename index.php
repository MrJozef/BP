<?php
    include "wcm/model/DBWrap.php";
    DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Absolutely not a Bachelor thesis</title>
</head>

<body>

    <div id="reg-uzivatela">
        <h1>Registrácia nového užívateľa</h1>
        <form method="post">
            <label>Nick:
                <input type="text" name="nick" minlength="4" maxlength="30" placeholder="Frenky22" required>
            </label>
            <label>Heslo:
                <input type="password" name="heslo" minlength="6" maxlength="30" required>
            </label>
            <label>Opakujte heslo:
                <input type="password" name="heslo-2" minlength="6" maxlength="30" required>
            </label>
            <label>E-mail:
                <input type="email" name="mail" placeholder="jano.fero@gmail.com" minlength="6" maxlength="60" required>
            </label>
            <!-- TODO recaptcha alebo iny antispam? -->
            <input type="submit" value="Registrovať">
            <!-- or <button id="udalostRegistrovat">Registrovať</button> -->
        </form>
    </div>

</body>
</html>