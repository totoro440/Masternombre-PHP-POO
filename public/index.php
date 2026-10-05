<?php
// declare(strict_types=1);

// Point d'entrée unique de l'application
require_once __DIR__ . '/../vendor/autoload.php';

use Jenssegers\Blade\Blade;
use App\Metier\Game;
use App\Metier\ScoreRepository;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Configure le cookie de session pour expirer dès que le navigateur se ferme (0 = fin de session navigateur)
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',  // Laisse vide = domaine courant
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
error_log("=== DÉBUT REQUEST ===");
error_log("Session ID: " . session_id());
error_log("Session game avant: " . (isset($_SESSION['game']) ? 'OUI' : 'NON'));

// Configuration du moteur de templates Blade
$views = __DIR__ . '/../templates';
$cache = __DIR__ . '/../cache';
$blade = new Blade($views, $cache);

// Gestion de la réinitialisation ou création de la partie
error_log("Session game isset: " . (isset($_SESSION['game']) ? 'OUI' : 'NON'));
if (!isset($_SESSION['game']) || isset($_POST['recommencer'])) {
    // Génération technique du secret à 5 chiffres (votre fonction initiale adaptée)
    $secretAleatoire = (string)rand(10000, 99999);
    $_SESSION['game'] = new Game($secretAleatoire,10,3600);
}

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


// TRAITEMENT DU SCORE EN FIN DE PARTIE
$scoreRepository = new ScoreRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer_score'])) {
    $nomJoueur = trim($_POST['nom_joueur'] ?? 'Anonyme');
    
    if ($game->isVictory() && !empty($nomJoueur)) {
        // Le métier calcule le nombre de coups joués
        $score = $game->getScore();
        
        // Enregistrement via le repository
        $scoreRepository->save($nomJoueur, $score);
        
        // Optionnel : on réinitialise pour éviter le double envoi
        unset($_SESSION['game']);
        header('Location: index.php');
        exit;
    }
}

// On récupère le Top 3 pour l'envoyer à la vue Blade
$topScores = $scoreRepository->getBestScores();

error_log("Session game après: " . (isset($_SESSION['game']) ? 'OUI' : 'NON'));
error_log("=== FIN REQUEST ===");

// Affichage final via Blade
echo $blade->render('vue', [
    'game' => $game,
    'errorMsg' => $errorMsg,
    'topScores' => $topScores
]);