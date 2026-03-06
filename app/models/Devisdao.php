<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/devis.php';
require_once __DIR__ . '/clientDao.php';

class DevisDao {
    
    // Récupère tous les devis actifs
    public static function recupTousLesDevis() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT d.*, p.nom as prospect_nom, p.prenom as prospect_prenom, p.email as prospect_email
            FROM devis d
            LEFT JOIN prospect p ON d.id_prospect = p.id_prospect
            WHERE d.archive = 0
            ORDER BY d.date_creation DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les devis archivés
    public static function recupDevisArchives() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT d.*, p.nom as prospect_nom, p.prenom as prospect_prenom
            FROM devis d
            LEFT JOIN prospect p ON d.id_prospect = p.id_prospect
            WHERE d.archive = 1
            ORDER BY d.date_creation DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un devis par ID
    public static function getDevisById($id_devis) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT d.*, p.nom as prospect_nom, p.prenom as prospect_prenom, p.email as prospect_email,
                   p.telephone as prospect_telephone, p.adresse as prospect_adresse
            FROM devis d
            LEFT JOIN prospect p ON d.id_prospect = p.id_prospect
            WHERE d.id_devis = :id
        ");
        $stmt->bindValue(':id', $id_devis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Récupère les devis d'un prospect
    public static function getDevisByProspectId($id_prospect) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT * FROM devis 
            WHERE id_prospect = :id AND archive = 0
            ORDER BY date_creation DESC
        ");
        $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Génère un numéro de devis unique
    public static function generateNumeroDevis() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT MAX(id_devis) as max_id FROM devis");
        $stmt->execute();
        $result = $stmt->fetch();
        $nextId = ($result['max_id'] ?? 0) + 1;
        return 'DEV-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    // Créer un nouveau devis
    public static function createDevis($devis) {
        if (
            empty($devis->getIdProspect()) ||
            empty($devis->getTypeVehicule()) ||
            empty($devis->getIdVehicule())
        ) {
            return ['success' => false, 'message' => 'Données manquantes pour créer le devis.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            
            // Générer un numéro de devis
            $numeroDevis = self::generateNumeroDevis();

            $stmt = $pdo->prepare("
                INSERT INTO devis (
                    numero_devis, id_prospect, type_vehicule, id_vehicule,
                    marque_vehicule, modele_vehicule, prix_vehicule, remise,
                    prix_final, statut, commentaire, archive
                )
                VALUES (
                    :numero_devis, :id_prospect, :type_vehicule, :id_vehicule,
                    :marque, :modele, :prix, :remise,
                    :prix_final, :statut, :commentaire, 0
                )
            ");

            $stmt->bindValue(':numero_devis', $numeroDevis);
            $stmt->bindValue(':id_prospect', $devis->getIdProspect(), PDO::PARAM_INT);
            $stmt->bindValue(':type_vehicule', $devis->getTypeVehicule());
            $stmt->bindValue(':id_vehicule', $devis->getIdVehicule(), PDO::PARAM_INT);
            $stmt->bindValue(':marque', $devis->getMarqueVehicule());
            $stmt->bindValue(':modele', $devis->getModeleVehicule());
            $stmt->bindValue(':prix', $devis->getPrixVehicule());
            $stmt->bindValue(':remise', $devis->getRemise());
            $stmt->bindValue(':prix_final', $devis->getPrixFinal());
            $stmt->bindValue(':statut', $devis->getStatut());
            $stmt->bindValue(':commentaire', $devis->getCommentaire());

            $stmt->execute();

            return ['success' => true, 'message' => 'Devis créé avec succès.', 'numero_devis' => $numeroDevis];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    // Mettre à jour un devis
    public static function updateDevis($devis) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE devis 
                SET remise = :remise, prix_final = :prix_final, 
                    commentaire = :commentaire, statut = :statut
                WHERE id_devis = :id
            ");

            $stmt->bindValue(':remise', $devis->getRemise());
            $stmt->bindValue(':prix_final', $devis->getPrixFinal());
            $stmt->bindValue(':commentaire', $devis->getCommentaire());
            $stmt->bindValue(':statut', $devis->getStatut());
            $stmt->bindValue(':id', $devis->getIdDevis(), PDO::PARAM_INT);

            $stmt->execute();

            return ['success' => true, 'message' => 'Devis mis à jour avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
        }
    }

    // Valider un devis et convertir le prospect en client
    public static function validerDevis($id_devis) {
        try {
            $pdo = Db::seConnecterBdd();
            $pdo->beginTransaction();

            // Récupérer le devis et le prospect
            $devis = self::getDevisById($id_devis);
            if (!$devis) {
                $pdo->rollBack();
                return ['success' => false, 'message' => 'Devis introuvable.'];
            }

            // Créer le client depuis le prospect
            $prospect = [
                'id_prospect' => $devis['id_prospect'],
                'nom' => $devis['prospect_nom'],
                'prenom' => $devis['prospect_prenom'],
                'email' => $devis['prospect_email'],
                'telephone' => $devis['prospect_telephone'] ?? '',
                'adresse' => $devis['prospect_adresse'] ?? '',
                'ville' => '',
                'code_postal' => ''
            ];

            $clientResult = ClientDao::createClientFromProspect($prospect);

            if (!$clientResult['success']) {
                // Si le client existe déjà, récupérer son ID
                if (isset($clientResult['client_id'])) {
                    $clientId = $clientResult['client_id'];
                } else {
                    $pdo->rollBack();
                    return $clientResult;
                }
            } else {
                $clientId = $clientResult['client_id'];
            }

            // Mettre à jour le devis
            $stmt = $pdo->prepare("
                UPDATE devis 
                SET statut = 'valide', 
                    id_client = :id_client, 
                    date_validation = NOW()
                WHERE id_devis = :id_devis
            ");
            $stmt->bindValue(':id_client', $clientId, PDO::PARAM_INT);
            $stmt->bindValue(':id_devis', $id_devis, PDO::PARAM_INT);
            $stmt->execute();

            $pdo->commit();

            return [
                'success' => true, 
                'message' => 'Devis validé et prospect converti en client avec succès.',
                'client_id' => $clientId
            ];

        } catch (PDOException $e) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    // Refuser un devis
    public static function refuserDevis($id_devis) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE devis SET statut = 'refuse' WHERE id_devis = :id");
            $stmt->bindValue(':id', $id_devis, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Devis refusé.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    // Archiver un devis
    public static function archiveDevis($id_devis) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE devis SET archive = 1 WHERE id_devis = :id");
            $stmt->bindValue(':id', $id_devis, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Devis archivé avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

    // Restaurer un devis
    public static function restoreDevis($id_devis) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE devis SET archive = 0 WHERE id_devis = :id");
            $stmt->bindValue(':id', $id_devis, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Devis restauré avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

    // Supprimer définitivement un devis
    public static function deleteDevis($id_devis) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM devis WHERE id_devis = :id");
        $stmt->bindValue(':id', $id_devis, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>