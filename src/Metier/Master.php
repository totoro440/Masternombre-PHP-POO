<?php
declare(strict_types=1);

namespace App\Metier;

use InvalidArgumentException;

class Master
{
    private string $secret;
    private int $secretSize;

    public function __construct(string $secret) 
    {
        if (empty($secret)) {
            throw new InvalidArgumentException("Le secret de la partie ne peut pas être vide.");
        }
        $this->secret = $secret;
        $this->secretSize = strlen($secret);
    }

    public function getClues(string $proposal): array 
    {
        if (strlen($proposal) !== $this->secretSize) {
            throw new InvalidArgumentException("La proposition doit faire exactement {$this->secretSize} caractères.");
        }

        $wellPlaced = 0;
        $isPresent = 0;
        $wordToFindP = "";
        $wordToTryP = "";

        // Étape 1 : Analyse des éléments bien placés 
        for ($i = 0; $i < $this->secretSize; $i++) { 
            if ($this->secret[$i] === $proposal[$i]) {
                $wellPlaced++;
                $wordToFindP .= "$";
                $wordToTryP .= "%";
            } else {
                $wordToFindP .= $this->secret[$i];
                $wordToTryP .= $proposal[$i];
            }
        }

        // Étape 2 : Analyse des éléments présents mais mal placés
        for ($i = 0; $i < $this->secretSize; $i++) { 
            $pos = strpos($wordToFindP, $wordToTryP[$i]); 
            if ($pos !== false) {
                $isPresent++;
                $wordToFindP[$pos] = "$";
            }
        }

        return [
            'well_placed' => $wellPlaced,
            'is_present'  => $isPresent
        ];
    }

    public function getSecretSize(): int 
    {
        return $this->secretSize;
    }

    public function getSecret(): string 
    {
        return $this->secret;
    }
}