<?php
session_start();

include 'inc/Game.php';
include 'inc/Phrase.php';

if (isset($_POST['start'])) {
    session_unset();
}

$_SESSION['selected'] ??= [];
$_SESSION['phrase'] ??= '';

if (isset($_POST['key'])) {
    $_SESSION['selected'][] = $_POST['key'];
}

$phrase = new Phrase($_SESSION['phrase'], $_SESSION['selected']);
$game = new Game($phrase);

$_SESSION['phrase'] = $phrase->currentPhrase;
?>

<!DOCTYPE html>
<html lang="en" onkeydown="document.querySelector('button[value='+event.key+']').click()">
    <head>
        <meta charset="utf-8">
        <title>Phrase Hunter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    </head>

    <body>
        <div class="main-container">
            <h2 class="header">Phrase Hunter</h2>
            <?php
                if ($game->gameOver()) {
                    echo $game->gameOver();
                    echo $game->restartGame();
                } else {
                    echo $phrase->addPhraseToDisplay();
                    echo $game->displayKeyboard();
                    echo $game->displayScore();
                }
            ?>
        </div>
    </body>
</html>
