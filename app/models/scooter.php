<?php
require_once __DIR__ . '/Vehicule.php';

class Scooter extends Vehicule {
    private int $cylindree;
    private bool $coffre;

    public function __construct($id_vehicule, $marque, $modele, $prix, $annee, $couleur, $cylindree, $coffre, $archive = 0) {
        parent::__construct($id_vehicule, $marque, $modele, $prix, $annee, $couleur, $archive);
        $this->cylindree = $cylindree;
        $this->coffre = $coffre;
    }

    // Getters spécifiques à Scooter
    public function getCylindree() {
        return $this->cylindree;
    }

    public function getCoffre() {
        return $this->coffre;
    }

    // Setters spécifiques à Scooter
    public function setCylindree($cylindree) {
        $this->cylindree = $cylindree;
    }

    public function setCoffre($coffre) {
        $this->coffre = $coffre;
    }
}
?>