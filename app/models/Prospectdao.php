<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Prospect.php';

class ProspectDao {
    
    // Récupère tous les prospects actifs
    public static function recupTousLesProspects() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT * FROM prospect 
            WHERE archive = 0 
            ORDER BY date_creation DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les prospects archivés
    public static function recupProspectsArchives() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT * FROM prospect 
            WHERE archive = 1 
            ORDER BY date_creation DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un prospect par ID
    public static function getProspectById($id_prospect) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM prospect WHERE id_prospect = :id");
        $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Créer un nouveau prospect
    public static function createProspect($prospect) {
        if (
            empty($prospect->getNom()) ||
            empty($prospect->getPrenom()) ||
            empty($prospect->getEmail())
        ) {
            return ['success' => false, 'message' => 'Nom, prénom et email sont obligatoires.'];
        }

        if (!filter_var($prospect->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Format d\'email invalide.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                INSERT INTO prospect (nom, prenom, email, telephone, adresse, ville, code_postal, archive)
                VALUES (:nom, :prenom, :email, :telephone, :adresse, :ville, :code_postal, 0)
            ");
            $stmt->bindValue(':nom', $prospect->getNom());
            $stmt->bindValue(':prenom', $prospect->getPrenom());
            $stmt->bindValue(':email', $prospect->getEmail());
            $stmt->bindValue(':telephone', $prospect->getTelephone());
            $stmt->bindValue(':adresse', $prospect->getAdresse());
            $stmt->bindValue(':ville', $prospect->getVille());
            $stmt->bindValue(':code_postal', $prospect->getCodePostal());

            $stmt->execute();

            return ['success' => true, 'message' => 'Prospect ajouté avec succès.'];

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
            }
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    // Mettre à jour un prospect
    public static function updateProspect($prospect) {
        if (
            empty($prospect->getIdProspect()) ||
            empty($prospect->getNom()) ||
            empty($prospect->getPrenom()) ||
            empty($prospect->getEmail())
        ) {
            return ['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.'];
        }

        if (!filter_var($prospect->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Format d\'email invalide.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE prospect 
                SET nom = :nom, prenom = :prenom, email = :email, 
                    telephone = :telephone, adresse = :adresse, 
                    ville = :ville, code_postal = :code_postal
                WHERE id_prospect = :id
            ");

            $stmt->bindValue(':nom', $prospect->getNom());
            $stmt->bindValue(':prenom', $prospect->getPrenom());
            $stmt->bindValue(':email', $prospect->getEmail());
            $stmt->bindValue(':telephone', $prospect->getTelephone());
            $stmt->bindValue(':adresse', $prospect->getAdresse());
            $stmt->bindValue(':ville', $prospect->getVille());
            $stmt->bindValue(':code_postal', $prospect->getCodePostal());
            $stmt->bindValue(':id', $prospect->getIdProspect(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Prospect mis à jour avec succès.'];
            } else {
                return ['success' => false, 'message' => 'Aucune modification effectuée.'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
            }
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
        }
    }

    // Archiver un prospect
    public static function archiveProspect($id_prospect) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE prospect SET archive = 1 WHERE id_prospect = :id");
            $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Prospect archivé avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

    // Restaurer un prospect
    public static function restoreProspect($id_prospect) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE prospect SET archive = 0 WHERE id_prospect = :id");
            $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Prospect restauré avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

    // Supprimer définitivement un prospect
    public static function deleteProspect($id_prospect) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM prospect WHERE id_prospect = :id");
        $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Vérifier si un prospect a des devis
    public static function prospectHasDevis($id_prospect) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM devis WHERE id_prospect = :id");
        $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
?>