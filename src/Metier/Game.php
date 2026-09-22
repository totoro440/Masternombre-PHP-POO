<?php

namespace App\Metier;

use Exception;
use App\Metier\Master;

class Game 
{
    private Master $rules;
    private int $maxTries;
    private array $historique = [];
    private bool $endGame = false;
    private bool $victory = false;
    private string $clue1;
    private string $clue2;
    private string $clue3;

    public function __construct(string $secret, int $maxTries = 10) 
    {
        $this->rules = new Master($secret);
        $this->maxTries = $maxTries;
        $even = ($secret%2==0) ? "pair" : "impair";
        $this->clue1 = "Le maximum d'occurence du même chiffre dans le nombre a trouvé est {$this->max_same_number()}" ;
        $this->clue3 = "Le nombre a trouvé est {$even}";
    }

    public function play(string $proposal): array 
    {
        if ($this->endGame) {
            throw new Exception("La partie est déjà terminée.");
        }

        $clues = $this->rules->getClues($proposal);
        
        $this->historique[] = [
            'proposal' => $proposal,
            'clues'     => $clues
        ];

        // Règle de victoire : tous les chiffres sont bien placés
        if ($clues['well_placed'] === $this->rules->getSecretSize()) {
            $this->victory = true;
            $this->endGame = true;
        } 
        // Règle de défaite : nombre maximum de tentatives atteint
        elseif (count($this->historique) >= $this->maxTries) {
            $this->endGame = true;
        }

        if(count($this->historique)===6){
            $small = ($this->rules->getSecret() > $proposal) ? "strictement grand que" : "petit ou égal à";
            $this->clue2 = "Le nombre à trouver est {$small} {$proposal}";
        }

        return $clues;
    }

    /**
     * RÈGLE MÉTIER : Génère un indice selon le numéro du tour actuel
     */
    public function getClues(): ?array
    {
        $clues = [];
        $tourActuel = count($this->historique);

        // A partir du tour 3 : on révèle l'indice 1
        if ($tourActuel >= 3) {
            $clues[] = $this->clue1;
        }
        
        // A partir du tour 6 : on révèle l'indice 2
        if ($tourActuel >= 6) {
            $clues[] = $this->clue2;
        }
        
        // A partir du tour 6 : on révèle l'indice 3
        if ($tourActuel >= 9) {
            $clues[] = $this->clue3;
        }

        return $clues;
    }

    public function isVictory(): bool { return $this->victory; }
    public function isOver(): bool { return $this->endGame; }
    public function getHistorique(): array { return $this->historique; }
    public function getRules(): Master { return $this->rules; }
    public function getRemainingTries(): int { return $this->maxTries - count($this->historique); }

    private function max_same_number(){
        $max_same = 0;
        $secret = (string) $this->rules->getSecret();
        for ($i=0; $i < $this->rules->getSecretSize(); $i++) { 
            $max_same = max($max_same,substr_count($secret, $secret[$i]));
        }
        return $max_same;  
    }
}