<?php
include_once "../../controller/ControllerRouter.php";
session_start();

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'logout') {
        $_SESSION = array();
        session_destroy();
        echo("Boli ste odhlásený");
        header("Location: index.php");
    }
}

$router = new ControllerRouter();
$router->process([$_SERVER['REQUEST_URI']]);
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Administrátorské rozhranie</title>
</head>

<body>
<header>
    <!--<nav>
        <ul>

        </ul>
    </nav>-->
</header>

<main>

</main>

<?php if (isset($_SESSION['uzivatel'])) { ?>
    <footer>
        <ul>
            <li>
                <button value="o-blogu">O blogu</button>
            </li>
            <li>
                <button value="zoznam-clankov">Články</button>
            </li>
            <li>
                <button value="vytvor-clanok">Napíš článok</button>
            </li>
            <li>
                <button value="koment-sekcia">Komentárová sekcia</button>
            </li>
            <li>
                <button value="zmen-heslo">Spravovať profil</button>
            </li>
            <li><p>Vitajte, <?= ($_SESSION['uzivatel']) ?>!</p></li>
            <li><a href="admin.php?action=logout">Odhlásiť sa</a></li>
        </ul>
    </footer>
<?php } ?>
</body>
</html>
