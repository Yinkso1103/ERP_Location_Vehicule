<?php
class Vehicule {
    protected int $id_vehicule;
    protected string $marque;
    protected string $modele;
    protected float $prix;
    protected int $annee;
    protected string $couleur;
    protected int $archive;

    public function __construct($id_vehicule, $marque, $modele, $prix, $annee, $couleur, $archive = 0) {
        $this->id_vehicule = $id_vehicule;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prix = $prix;
        $this->annee = $annee;
        $this->couleur = $couleur;
        $this->archive = $archive;
    }

    // Getters
    public function getIdVehicule() {
        return $this->id_vehicule;
    }

    public function getMarque() {
        return $this->marque;
    }

    public function getModele() {
        return $this->modele;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function getAnnee() {
        return $this->annee;
    }

    public function getCouleur() {
        return $this->couleur;
    }

    public function getArchive() {
        return $this->archive;
    }

    // Setters
    public function setMarque($marque) {
        $this->marque = $marque;
    }

    public function setModele($modele) {
        $this->modele = $modele;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setAnnee($annee) {
        $this->annee = $annee;
    }

    public function setCouleur($couleur) {
        $this->couleur = $couleur;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
    }
}
?>