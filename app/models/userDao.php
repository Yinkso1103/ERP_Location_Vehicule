<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Role.php';
require_once __DIR__ . '/Utilisateur.php';

class UserDao {
    
    // Récupère uniquement les utilisateurs actifs (non archivés)
    public static function recupTousLesUtilisateurs() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.mot_de_passe, u.archive, r.id_role, r.nom_role 
            FROM utilisateur u
            JOIN role r ON u.id_role = r.id_role
            WHERE u.archive = 0
            ORDER BY u.id_utilisateur DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère uniquement les utilisateurs archivés
    public static function recupUtilisateursArchives() {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.mot_de_passe, u.archive, r.id_role, r.nom_role 
            FROM utilisateur u
            JOIN role r ON u.id_role = r.id_role
            WHERE u.archive = 1
            ORDER BY u.id_utilisateur DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserById($id_utilisateur) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("
            SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.mot_de_passe, u.archive, u.id_role, r.nom_role 
            FROM utilisateur u
            JOIN role r ON u.id_role = r.id_role 
            WHERE u.id_utilisateur = :id_utilisateur
        ");
        $stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function createUser($utilisateur) {
        if (
            empty($utilisateur->getNomUser()) ||
            empty($utilisateur->getPrenomUser()) ||
            empty($utilisateur->getEmailUser()) ||
            empty($utilisateur->getRole()) ||
            empty($utilisateur->getPasswordUser())
        ) {
            return ['success' => false, 'message' => 'Tous les champs sont obligatoires.'];
        }

        if (!filter_var($utilisateur->getEmailUser(), FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Format d\'email invalide.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, id_role, archive)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :id_role, 0)
            ");
            $stmt->bindValue(':nom', $utilisateur->getNomUser());
            $stmt->bindValue(':prenom', $utilisateur->getPrenomUser());
            $stmt->bindValue(':email', $utilisateur->getEmailUser());
            $stmt->bindValue(':mot_de_passe', $utilisateur->getPasswordUser());
            $stmt->bindValue(':id_role', $utilisateur->getRole(), PDO::PARAM_INT);

            $stmt->execute();

            return ['success' => true, 'message' => 'Utilisateur ajouté avec succès.'];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

    public static function updateUser($utilisateur) {
        if (
            empty($utilisateur->getIdUser()) ||
            empty($utilisateur->getNomUser()) ||
            empty($utilisateur->getPrenomUser()) ||
            empty($utilisateur->getEmailUser()) ||
            empty($utilisateur->getRole()) ||
            empty($utilisateur->getPasswordUser())
        ) {
            return ['success' => false, 'message' => 'Tous les champs sont obligatoires.'];
        }

        if (!filter_var($utilisateur->getEmailUser(), FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Format d\'email invalide.'];
        }

        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("
                UPDATE utilisateur 
                SET nom = :nom, prenom = :prenom, email = :email, 
                    id_role = :role, mot_de_passe = :mot_de_passe
                WHERE id_utilisateur = :id
            ");

            $stmt->bindValue(':nom', $utilisateur->getNomUser());
            $stmt->bindValue(':prenom', $utilisateur->getPrenomUser());
            $stmt->bindValue(':email', $utilisateur->getEmailUser());
            $stmt->bindValue(':role', $utilisateur->getRole(), PDO::PARAM_INT);
            $stmt->bindValue(':mot_de_passe', $utilisateur->getPasswordUser());
            $stmt->bindValue(':id', $utilisateur->getIdUser(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Utilisateur mis à jour avec succès.'];
            } else {
                return ['success' => false, 'message' => 'Aucune modification effectuée.'];
            }
        } catch (PDOException $e) {
            error_log("updateUser error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
        }
    }

    // Archive un utilisateur (au lieu de le supprimer)
    public static function archiveUser($id_utilisateur) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE utilisateur SET archive = 1 WHERE id_utilisateur = :id_utilisateur");
            $stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Utilisateur archivé avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'archivage.'];
        }
    }

    // Restaure un utilisateur archivé
    public static function restoreUser($id_utilisateur) {
        try {
            $pdo = Db::seConnecterBdd();
            $stmt = $pdo->prepare("UPDATE utilisateur SET archive = 0 WHERE id_utilisateur = :id_utilisateur");
            $stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Utilisateur restauré avec succès.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la restauration.'];
        }
    }

    // Suppression définitive (à utiliser avec précaution)
    public static function deleteUser($id_utilisateur) {
        $pdo = Db::seConnecterBdd();
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
        $stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
 * Récupère un utilisateur par son email (pour l'authentification)
 */
public static function getUserByEmail($email) {
    $pdo = Db::seConnecterBdd();
    $stmt = $pdo->prepare("
        SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.mot_de_passe, u.archive, u.id_role, r.nom_role 
        FROM utilisateur u
        JOIN role r ON u.id_role = r.id_role 
        WHERE u.email = :email
    ");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}

}