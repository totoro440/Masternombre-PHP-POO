<?php
namespace App\Metier;

use PDO;

class ScoreRepository implements ScoreRepositoryInterface
{
    private PDO $pdo;

    public function __construct() 
    {
        $databasePath = __DIR__ . '/../../database/database.sqlite';
        $this->pdo = new PDO("sqlite:" . $databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS scores (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom_joueur TEXT NOT NULL,
            score INTEGER NOT NULL,
            date_partie DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function save(string $nom, int $score): void 
    {
        $stmt = $this->pdo->prepare("INSERT INTO scores (nom_joueur, score) VALUES (:nom, :score)");
        $stmt->execute([
            'nom' => htmlspecialchars($nom),
            'score' => $score
        ]);
    }

    public function getBestScores(int $nbTop = 3): array 
    {
        $stmt = $this->pdo->query("SELECT nom_joueur, score FROM scores ORDER BY score DESC, date_partie DESC LIMIT {$nbTop}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}