<?php
class Prospect {
    private int $id_prospect;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $adresse;
    private string $ville;
    private string $code_postal;
    private string $date_creation;
    private int $archive;

    public function __construct(
        $id_prospect, 
        $nom, 
        $prenom, 
        $email, 
        $telephone = '', 
        $adresse = '', 
        $ville = '', 
        $code_postal = '', 
        $date_creation = '', 
        $archive = 0
    ) {
        $this->id_prospect = $id_prospect;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->date_creation = $date_creation;
        $this->archive = $archive;
    }

    // Getters
    public function getIdProspect() {
        return $this->id_prospect;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getVille() {
        return $this->ville;
    }

    public function getCodePostal() {
        return $this->code_postal;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    public function getArchive() {
        return $this->archive;
    }

    public function isArchived() {
        return $this->archive === 1;
    }

    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setVille($ville) {
        $this->ville = $ville;
    }

    public function setCodePostal($code_postal) {
        $this->code_postal = $code_postal;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
    }
}
?>