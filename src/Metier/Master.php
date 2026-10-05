<?php
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

        // Appel des deux sous-étapes métiers isolées
        [$wellPlaced,$wordToFindP,$wordToTryP] = $this->processWellplaced($proposal);
        $isPresent = $this->countPresent($wordToFindP,$wordToTryP);

        return [
            'well_placed' => $wellPlaced,
            'is_present'  => $isPresent
        ];        
    }

    /**
     * ÉTAPE 1 (Privée) : Analyse et masquage des éléments bien placés
     */
    private function processWellplaced(string $proposal): array
    {
        $wellPlaced = 0;
        $wordToFindP = "";
        $wordToTryP ="";

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

        return [$wellPlaced,$wordToFindP,$wordToTryP];
    }

    /**
     * ÉTAPE 2 (Privée) : Analyse et masquage des éléments présents mais mal placés
     */
    private function countPresent(string $wordToFindP,string $wordToTryP): int
    {
        $isPresent = 0;

        for ($i = 0; $i < $this->secretSize; $i++) { 
            $pos = strpos($wordToFindP, $wordToTryP[$i]); 
            if ($pos !== false) {
                $isPresent++;
                $wordToFindP[$pos] = "$";
            }
        }

        return $isPresent;
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