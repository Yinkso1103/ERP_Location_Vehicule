<?php
class Utilisateur {
    private int $id_utilisateur;
    private int $role;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe;
    private int $archive;


    public function __construct($id_utilisateur, $nom, $prenom, $email, $mot_de_passe, $role, $archive = 0) {
        $this->id_utilisateur = $id_utilisateur;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
        $this->archive = $archive;
    }

    // --- Getters ---
    public function getIdUser() {
        return $this->id_utilisateur;
    }

    public function getNomUser() {
        return $this->nom;
    }

    public function getPrenomUser() {
        return $this->prenom;
    }

    public function getEmailUser() {
        return $this->email;
    }

    public function getPasswordUser() {
        return $this->mot_de_passe;
    }

    public function getRole() {
        return $this->role;
    }
    public function getArchive() {
        return $this->archive;
    }

    public function isArchived() {
        return $this->archive === 1;
    }


    // --- Setters ---
    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMotDePasse($mot_de_passe) {
        $this->mot_de_passe = $mot_de_passe;
    }

    public function setRole($role) {
        $this->role = $role;
    }
    public function setArchive($archive) {
        $this->archive = $archive;
    }

}