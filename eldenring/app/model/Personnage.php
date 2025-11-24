<?php
class Personnage {
    public $nom;
    public $pv;
    public $force;

    public function __construct($nom, $pv, $force) {
        $this->nom = $nom;
        $this->pv = $pv;
        $this->force = $force;
    }

    public function attaquer($enemi) {
        $enemi->pv -= $this->force;
        return $this->nom . " attaque" . $enemi->nom . "!";
    }
}