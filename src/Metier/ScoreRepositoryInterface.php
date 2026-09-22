<?php
declare(strict_types=1);

interface ScoreRepositoryInterface {
    /**
     * Enregistrer le score d'un joueur.
     */
    public function save(string $pseudo, int $score): void;

    /**
     * Récupérer le Top 3 des meilleurs scores.
     */
    public function getBestScores(): array;
}