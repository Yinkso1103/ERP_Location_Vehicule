<?php
class Client {
    private int $id_client;
    private int $id_prospect;
    private string $numero_client;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $adresse;
    private string $ville;
    private string $code_postal;
    private string $date_conversion;
    private int $archive;

    public function __construct(
        $id_client,
        $id_prospect,
        $numero_client,
        $nom,
        $prenom,
        $email,
        $telephone = '',
        $adresse = '',
        $ville = '',
        $code_postal = '',
        $date_conversion = '',
        $archive = 0
    ) {
        $this->id_client = $id_client;
        $this->id_prospect = $id_prospect;
        $this->numero_client = $numero_client;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->date_conversion = $date_conversion;
        $this->archive = $archive;
    }

    // Getters
    public function getIdClient() {
        return $this->id_client;
    }

    public function getIdProspect() {
        return $this->id_prospect;
    }

    public function getNumeroClient() {
        return $this->numero_client;
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

    public function getDateConversion() {
        return $this->date_conversion;
    }

    public function getArchive() {
        return $this->archive;
    }

    public function isArchived() {
        return $this->archive === 1;
    }

    // Setters
    public function setNumeroClient($numero_client) {
        $this->numero_client = $numero_client;
    }

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