<?php
require_once __DIR__ . '/../model/Personnage.php';

class eldenController {
    public function personnage() {
        $nom = "sans-éclat";
        $title = "Page Personnage";
        $description = "Découvrez le personnage principal de Elden Ring.";
        include __DIR__ . '/../view/personnage.php';
    }

    public function boss() {
        $nom = "renalla";
        $title = "Page Boss";
        $description = "Affrontez les boss les plus puissants d’Elden Ring.";
        include __DIR__ . '/../view/boss.php';
    }

    public function combat() {
        
        $title = "Page Combat";
        $description = "Suivez le combat épique entre Sans-Éclat et Renalla.";
        $joueur = new Personnage("sans-éclat", 50, 10);
        $boss   = new Personnage("renalla", 40, 8);

        $log = [];

        
        while ($joueur->pv > 0 && $boss->pv > 0) {

            $log[] = $joueur->attaquer($boss);

            if ($boss->pv <= 0) {
                $log[] = "victoire ! " . $boss->nom . " est vaincu";
                break;
            }

            $log[] = $boss->attaquer($joueur);

            if ($joueur->pv <= 0) {
                $log[] = "game over... " . $joueur->nom . " est mort.";
                break;
            }
        }

        include __DIR__ . '/../view/combat.php';
    }
}
