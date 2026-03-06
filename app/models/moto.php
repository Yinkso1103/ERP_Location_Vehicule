<?php
require_once __DIR__ . '/vehicule.php';

class Moto extends Vehicule {
    private int $cylindree;
    private string $type_moto;

    public function __construct($id_vehicule, $marque, $modele, $prix, $annee, $couleur, $cylindree, $type_moto, $archive = 0) {
        parent::__construct($id_vehicule, $marque, $modele, $prix, $annee, $couleur, $archive);
        $this->cylindree = $cylindree;
        $this->type_moto = $type_moto;
    }

    // Getters spécifiques à Moto
    public function getCylindree() {
        return $this->cylindree;
    }

    public function getTypeMoto() {
        return $this->type_moto;
    }

    // Setters spécifiques à Moto
    public function setCylindree($cylindree) {
        $this->cylindree = $cylindree;
    }

    public function setTypeMoto($type_moto) {
        $this->type_moto = $type_moto;
    }
}
?>