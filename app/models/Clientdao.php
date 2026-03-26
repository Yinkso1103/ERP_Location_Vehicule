<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Client.php';

class ClientDao {
    
    // Récupère tous les clients actifs
    public static function recupTousLesClients() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT * FROM client 
            WHERE archive = 0 
            ORDER BY date_conversion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les clients archivés
    public static function recupClientsArchives() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT * FROM client 
            WHERE archive = 1 
            ORDER BY date_conversion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un client par ID
    public static function getClientById($id_client) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = :id");
        $stmt->bindValue(':id', $id_client, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Récupère un client par son ID prospect
    public static function getClientByProspectId($id_prospect) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id_prospect = :id");
        $stmt->bindValue(':id', $id_prospect, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Génère un numéro de client unique
    public static function generateNumeroClient() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("SELECT MAX(id_client) as max_id FROM client");
        $stmt->execute();
        $result = $stmt->fetch();
        $nextId = ($result['max_id'] ?? 0) + 1;
        return 'CLI-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    // Créer un client depuis un prospect (conversion)
    public static function createClientFromProspect($prospect) {
        try {
            $pdo = Db::seConnecterBdd();
            
            // Vérifier si le prospect n'est pas déjà client
            $existingClient = self::getClientByProspectId($prospect['id_prospect']);
            if ($existingClient) {
                return [
                    'success' => false, 
                    'message' => 'Ce prospect est déjà client.',
                    'client_id' => $existingClient['id_client']
                ];
            }

            // Générer un numéro de client
            $numeroClient = self::generateNumeroClient();

            $stmt = $pdo->prepare("
                INSERT INTO client (
                    id_prospect, numero_client, nom, prenom, email, 
                    telephone, adresse, ville, code_postal, archive
                )
                VALUES (
                    :id_prospect, :numero_client, :nom, :prenom, :email,
                    :telephone, :adresse, :ville, :code_postal, 0
                )
            ");

            $stmt->bindValue(':id_prospect', $prospect['id_prospect'], PDO::PARAM_INT);
            $stmt->bindValue(':numero_client', $numeroClient);
            $stmt->bindValue(':nom', $prospect['nom']);
            $stmt->bindValue(':prenom', $prospect['prenom']);
            $stmt->bindValue(':email', $prospect['email']);
            $stmt->bindValue(':telephone', $prospect['telephone'] ?? '');
            $stmt->bindValue(':adresse', $prospect['adresse'] ?? '');
            $stmt->bindValue(':ville', $prospect['ville'] ?? '');
            $stmt->bindValue(':code_postal', $prospect['code_postal'] ?? '');

            $stmt->execute();
            $clientId = $pdo->lastInsertId();

            return [
                'success' => true, 
                'message' => 'Client créé avec succès.', 
                'client_id' => $clientId,
                'numero_client' => $numeroClient
            ];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    // Mettre à jour un client
    public static function updateClient($client) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE client 
                SET nom = :nom, prenom = :prenom, email = :email, 
                    telephone = :telephone, adresse = :adresse, 
                    ville = :ville, code_postal = :code_postal
                WHERE id_client = :id
            ");

            $stmt->bindValue(':nom', $client->getNom());
            $stmt->bindValue(':prenom', $client->getPrenom());
            $stmt->bindValue(':email', $client->getEmail());
            $stmt->bindValue(':telephone', $client->getTelephone());
            $stmt->bindValue(':adresse', $client->getAdresse());
            $stmt->bindValue(':ville', $client->getVille());
            $stmt->bindValue(':code_postal', $client->getCodePostal());
            $stmt->bindValue(':id', $client->getIdClient(), PDO::PARAM_INT);

            $stmt->execute();

            return ['success' => true, 'message' => 'Client mis à jour avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
        }
    }

    // Archiver un client
    public static function archiveClient($id_client) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE client SET archive = 1 WHERE id_client = :id");
            $stmt->bindValue(':id', $id_client, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Client archivé avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

    // Restaurer un client
    public static function restoreClient($id_client) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE client SET archive = 0 WHERE id_client = :id");
            $stmt->bindValue(':id', $id_client, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Client restauré avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

    // Supprimer définitivement un client
    public static function deleteClient($id_client) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM client WHERE id_client = :id");
        $stmt->bindValue(':id', $id_client, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>