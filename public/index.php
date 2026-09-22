<?php

// Point d'entrée unique de l'application
require_once __DIR__ . '/../vendor/autoload.php';

use App\Metier\Game;
use Jenssegers\Blade\Blade;

session_start();

// Configuration du moteur de templates Blade
$views = __DIR__ . '/../templates';
$cache = __DIR__ . '/../cache';
$blade = new Blade($views, $cache);

// Gestion de la réinitialisation ou création de la partie
if (!isset($_SESSION['game']) || isset($_POST['recommencer'])) {
    // Génération technique du secret à 5 chiffres (votre fonction initiale adaptée)
    $secretAleatoire = (string)rand(10000, 99999);
    $_SESSION['game'] = new Game($secretAleatoire, 10);
}

/** @var Partie $partie */
$game = $_SESSION['game'];
$errorMsg = null;

// Intercepter la soumission du formulaire utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proposal'])) {
    try {
        $proposal = trim($_POST['proposal']);
        $game->play($proposal);
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

// Affichage final via Blade
echo $blade->render('vue', [
    'game' => $game,
    'errorMsg' => $errorMsg
]);