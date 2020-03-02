<?php
    include "wcm/model/MyException.php";
    include "wcm/model/DBWrap.php";
    include "wcm/model/ManagerUser.php";
    session_start();

    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }


    $userDB = new ManagerUser();
    if (isset($_POST['nick'])) {
        $userDB->userSignUp($_POST['signup-nick'], $_POST['signup-passwd'], $_POST['signup-passwd2'], $_POST['signup-mail']);
    }
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

    <div id="signup">
        <h1>Administrátorská registrácia</h1>
        <form method="post">
            <label>Nick:
                <input type="text" name="signup-nick" minlength="4" maxlength="30" placeholder="Frenky22" required>
            </label>
            <label>Heslo:
                <input type="password" name="signup-passwd" minlength="6" maxlength="30" required>
            </label>
            <label>Opakujte heslo:
                <input type="password" name="signup-passwd2" minlength="6" maxlength="30" required>
            </label>
            <label>E-mail:
                <input type="email" name="signup-mail" placeholder="jano.fero@gmail.com" minlength="6" maxlength="60" required>
            </label>
            <input type="submit" value="Registrovať">
            <!-- or <button id="udalostRegistrovat">Registrovať</button> -->
        </form>
    </div>

    <div id="login">
        <h1>Administrátorské prihlasovanie</h1>
        <form method="post">
            <label>Nick:
                <input type="text" name="login-nick" minlength="4" maxlength="30" placeholder="Frenky22" required>
            </label>
            <label>Heslo:
                <input type="password" name="login-passwd" minlength="6" maxlength="30" required>
            </label>
            <input type="submit" value="Prihlásiť sa">
        </form>
    </div>

    <div id="user-settings"><!-- toto sa zobrazi iba prihlasenym -->
        <h1>Nastavenia účtu</h1>
        <h2>Zmena hesla</h2>
        <form method="post">
            <label>Aktuálne heslo:
                <input type="password" name="passwd-old" minlength="6" maxlength="30" required>
            </label>
            <label>Nové heslo:
                <input type="password" name="passwd-new" minlength="6" maxlength="30" required>
            </label>
            <label>Opakujte nové heslo:
                <input type="password" name="passwd-new2" minlength="6" maxlength="30" required>
            </label>
            <input type="submit" value="Zmeniť heslo">
        </form>

        <h2>Zmena nicku</h2>
        <form method="post">
            <label>Nový nick:
                <input type="text" name="nick-new" minlength="6" maxlength="30" required>
            </label>
            <label>Zadajte aj svoje heslo:
                <input type="password" name="nick-passwd" minlength="6" maxlength="30" required>
            </label>
            <input type="submit" value="Zmeniť nick">
        </form>

        <h2>Zmena E-mailovej adresy</h2>
        <form method="post">
            <label>Nová E-mailová adresa:
                <input type="email" name="mail-new" minlength="6" maxlength="60" required>
            </label>
            <label>Zadajte aj svoje heslo:
                <input type="password" name="mail-passwd" minlength="6" maxlength="30" required>
            </label>
            <input type="submit" value="Zmeniť E-mail">
        </form>

        <h2>Vymazanie profilu</h2>
        <p>Túto akciu nie je možné zvrátiť a zmazaný profil nie je možné obnoviť!</p>
        <form method="post">
            <label>Pre vymazanie profilu zadajte svoje heslo:
                <input type="password" name="delete-passwd" minlength="6" maxlength="30" required>
            </label>
            <input type="submit" value="Vymazať môj profil">
        </form>
    </div>
    

    <div id="create-article"><!-- toto sa zobrazi iba prihlasenym -->
        <h1>Vytvoriť nový článok</h1>
        <form method="post">
            <label>Nadpis článku:
                <input type="text" name="article-title" minlength="3" maxlength="200" placeholder="Formátovanie obrázkov" required>
            </label>
            <!--TODO tu bude musiet byt roletka aktualne existujucich kategorii
            <label>Kategória:
                <input type="text" name="article-category" minlength="3" maxlength="100" placeholder="programovanie jazykC" required>
            </label>-->
            <label>Text článku:
                <textarea name="article-text" minlength="50" maxlength="65535" placeholder="Sem napíšte celý Váš článok..." required></textarea>
            </label><!--todo tu pouzijeme nieco pokrocilejsie nez text area?-->
            <input type="submit" value="Pridať článok">
        </form>
    </div>

    <div id="create-category">
        <h1>Vytvoriť novú kategóriu</h1>
        <form method="post">
            <label>Názov kategórie:
                <input type="text" name="category-name" minlength="3" maxlength="100" placeholder="Web - 2. ročník" required>
            </label>
            <label>Popis kategórie:
                <textarea name="category-desc" minlength="20" maxlength="2000" placeholder="Sem napíšte popis ku kategórii - aké články obsahuje..." required></textarea>
            </label><!--todo tu je asi zbytocne pouzivat nieco ine nez texareu?-->
            <input type="submit" value="Vytvoriť kategóriu">
            <!--todo viditelnost na zaklikavanie?-->
        </form>
    </div>

    <footer>

    </footer>
</body>
</html>