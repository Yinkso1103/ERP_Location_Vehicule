<?php
class Devis {
    private int $id_devis;
    private string $numero_devis;
    private int $id_prospect;
    private ?int $id_client;
    private string $type_vehicule;
    private int $id_vehicule;
    private string $marque_vehicule;
    private string $modele_vehicule;
    private float $prix_vehicule;
    private float $remise;
    private float $prix_final;
    private string $statut;
    private string $date_creation;
    private ?string $date_validation;
    private string $commentaire;
    private int $archive;

    public function __construct(
        $id_devis,
        $numero_devis,
        $id_prospect,
        $type_vehicule,
        $id_vehicule,
        $marque_vehicule,
        $modele_vehicule,
        $prix_vehicule,
        $remise = 0.00,
        $prix_final = 0.00,
        $statut = 'en_attente',
        $id_client = null,
        $date_creation = '',
        $date_validation = null,
        $commentaire = '',
        $archive = 0
    ) {
        $this->id_devis = $id_devis;
        $this->numero_devis = $numero_devis;
        $this->id_prospect = $id_prospect;
        $this->id_client = $id_client;
        $this->type_vehicule = $type_vehicule;
        $this->id_vehicule = $id_vehicule;
        $this->marque_vehicule = $marque_vehicule;
        $this->modele_vehicule = $modele_vehicule;
        $this->prix_vehicule = $prix_vehicule;
        $this->remise = $remise;
        $this->prix_final = $prix_final > 0 ? $prix_final : $this->calculerPrixFinal();
        $this->statut = $statut;
        $this->date_creation = $date_creation;
        $this->date_validation = $date_validation;
        $this->commentaire = $commentaire;
        $this->archive = $archive;
    }

    // Calcule le prix final avec la remise
    private function calculerPrixFinal() {
        return $this->prix_vehicule - ($this->prix_vehicule * $this->remise / 100);
    }

    // Getters
    public function getIdDevis() {
        return $this->id_devis;
    }

    public function getNumeroDevis() {
        return $this->numero_devis;
    }

    public function getIdProspect() {
        return $this->id_prospect;
    }

    public function getIdClient() {
        return $this->id_client;
    }

    public function getTypeVehicule() {
        return $this->type_vehicule;
    }

    public function getIdVehicule() {
        return $this->id_vehicule;
    }

    public function getMarqueVehicule() {
        return $this->marque_vehicule;
    }

    public function getModeleVehicule() {
        return $this->modele_vehicule;
    }

    public function getPrixVehicule() {
        return $this->prix_vehicule;
    }

    public function getRemise() {
        return $this->remise;
    }

    public function getPrixFinal() {
        return $this->prix_final;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    public function getDateValidation() {
        return $this->date_validation;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }

    public function getArchive() {
        return $this->archive;
    }

    public function isArchived() {
        return $this->archive === 1;
    }

    // Setters
    public function setNumeroDevis($numero_devis) {
        $this->numero_devis = $numero_devis;
    }

    public function setIdClient($id_client) {
        $this->id_client = $id_client;
    }

    public function setRemise($remise) {
        $this->remise = $remise;
        $this->prix_final = $this->calculerPrixFinal();
    }

    public function setPrixFinal($prix_final) {
        $this->prix_final = $prix_final;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function setDateValidation($date_validation) {
        $this->date_validation = $date_validation;
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
    }
}
?>