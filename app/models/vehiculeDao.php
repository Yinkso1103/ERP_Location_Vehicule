<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Vehicule.php';
require_once __DIR__ . '/Moto.php';
require_once __DIR__ . '/Scooter.php';

class VehiculeDao {
    
        // Retourne toutes les motos actives (archive = 0), ordre décroissant

    public static function recupToutesLesMotos() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM moto WHERE archive = 0 ORDER BY id_moto DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

       // Retourne tous les scooters actifs

    public static function recupTousLesScooters() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM scooter WHERE archive = 0 ORDER BY id_scooter DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Récupère les motos archivées (archive = 1), pour la page "archives".
     
    public static function recupMotosArchivees() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM moto WHERE archive = 1 ORDER BY id_moto DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
     //Récupère les scooters archivés (archive = 1).
     
    public static function recupScootersArchives() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM scooter WHERE archive = 1 ORDER BY id_scooter DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère une moto par son ID (PARAM_INT → typage strict PDO)

    public static function getMotoById($id) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM moto WHERE id_moto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    
     //Récupère UN scooter précis via son id.
     
    public static function getScooterById($id) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM scooter WHERE id_scooter = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

       // Crée une moto (valide les champs obligatoires avant INSERT)

    public static function createMoto($moto) {
        if (empty($moto->getMarque()) || empty($moto->getModele()) || empty($moto->getPrix())) {
            return ['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                INSERT INTO moto (marque, modele, prix, annee, couleur, cylindree, type_moto, archive)
                VALUES (:marque, :modele, :prix, :annee, :couleur, :cylindree, :type_moto, 0)
            ");
            $stmt->bindValue(':marque', $moto->getMarque());
            $stmt->bindValue(':modele', $moto->getModele());
            $stmt->bindValue(':prix', $moto->getPrix());
            $stmt->bindValue(':annee', $moto->getAnnee(), PDO::PARAM_INT);
            $stmt->bindValue(':couleur', $moto->getCouleur());
            $stmt->bindValue(':cylindree', $moto->getCylindree(), PDO::PARAM_INT);
            $stmt->bindValue(':type_moto', $moto->getTypeMoto());
            $stmt->execute();

            return ['success' => true, 'message' => 'Moto ajoutée avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

        // Crée un scooter (coffre = booléen → PARAM_BOOL en PDO)

    public static function createScooter($scooter) {
        if (empty($scooter->getMarque()) || empty($scooter->getModele()) || empty($scooter->getPrix())) {
            return ['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                INSERT INTO scooter (marque, modele, prix, annee, couleur, cylindree, coffre, archive)
                VALUES (:marque, :modele, :prix, :annee, :couleur, :cylindree, :coffre, 0)
            ");
            $stmt->bindValue(':marque', $scooter->getMarque());
            $stmt->bindValue(':modele', $scooter->getModele());
            $stmt->bindValue(':prix', $scooter->getPrix());
            $stmt->bindValue(':annee', $scooter->getAnnee(), PDO::PARAM_INT);
            $stmt->bindValue(':couleur', $scooter->getCouleur());
            $stmt->bindValue(':cylindree', $scooter->getCylindree(), PDO::PARAM_INT);
            $stmt->bindValue(':coffre', $scooter->getCoffre(), PDO::PARAM_BOOL);
            $stmt->execute();

            return ['success' => true, 'message' => 'Scooter ajouté avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

        // Met à jour toutes les infos d'une moto

    public static function updateMoto($moto) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE moto 
                SET marque = :marque, modele = :modele, prix = :prix, 
                    annee = :annee, couleur = :couleur, cylindree = :cylindree, type_moto = :type_moto
                WHERE id_moto = :id
            ");
            $stmt->bindValue(':marque', $moto->getMarque());
            $stmt->bindValue(':modele', $moto->getModele());
            $stmt->bindValue(':prix', $moto->getPrix());
            $stmt->bindValue(':annee', $moto->getAnnee(), PDO::PARAM_INT);
            $stmt->bindValue(':couleur', $moto->getCouleur());
            $stmt->bindValue(':cylindree', $moto->getCylindree(), PDO::PARAM_INT);
            $stmt->bindValue(':type_moto', $moto->getTypeMoto());
            $stmt->bindValue(':id', $moto->getIdVehicule(), PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'message' => 'Moto mise à jour avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

       // Met à jour toutes les infos d'un scooter

    public static function updateScooter($scooter) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE scooter 
                SET marque = :marque, modele = :modele, prix = :prix, 
                    annee = :annee, couleur = :couleur, cylindree = :cylindree, coffre = :coffre
                WHERE id_scooter = :id
            ");
            $stmt->bindValue(':marque', $scooter->getMarque());
            $stmt->bindValue(':modele', $scooter->getModele());
            $stmt->bindValue(':prix', $scooter->getPrix());
            $stmt->bindValue(':annee', $scooter->getAnnee(), PDO::PARAM_INT);
            $stmt->bindValue(':couleur', $scooter->getCouleur());
            $stmt->bindValue(':cylindree', $scooter->getCylindree(), PDO::PARAM_INT);
            $stmt->bindValue(':coffre', $scooter->getCoffre(), PDO::PARAM_BOOL);
            $stmt->bindValue(':id', $scooter->getIdVehicule(), PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'message' => 'Scooter mis à jour avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    
     // Archive une moto = simple UPDATE qui passe archive à 1.
    
    public static function archiveMoto($id) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE moto SET archive = 1 WHERE id_moto = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Moto archivée avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

     // Archive un scooter = simple UPDATE qui passe archive à 1.
    public static function archiveScooter($id) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE scooter SET archive = 1 WHERE id_scooter = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Scooter archivé avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

        // Restaure une moto → archive = 0

    public static function restoreMoto($id) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE moto SET archive = 0 WHERE id_moto = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Moto restaurée avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

        // Restaure un scooter → archive = 0

    public static function restoreScooter($id) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE scooter SET archive = 0 WHERE id_scooter = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Scooter restauré avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

    
     //Supprime DÉFINITIVEMENT une moto (DELETE SQL réel, irréversible).
         public static function deleteMoto($id) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM moto WHERE id_moto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

         //Supprime DÉFINITIVEMENT un scooter (DELETE SQL réel, irréversible).

    public static function deleteScooter($id) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM scooter WHERE id_scooter = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>