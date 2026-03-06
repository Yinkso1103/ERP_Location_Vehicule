<?php
require_once __DIR__ . '/../models/userDao.php';

class UserController {

    // Liste des utilisateurs actifs
    public function index() {
        $users = UserDao::recupTousLesUtilisateurs();
       if (!isset($_SESSION['utilisateur'] ['id role']) == 1){
       require_once __DIR__ . '/../views/users/index.php';
       }else{
       require_once __DIR__ . '/../views/users/login.php';
       }
    }

    // Liste des utilisateurs archivés
    public function archived() {
        $users = UserDao::recupUtilisateursArchives();
        require_once __DIR__ . '/../views/users/archived.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/users/create.php';
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);    
        if ($id > 0) {
            $utilisateur = UserDao::getUserById($id);
            if (!$utilisateur) {
                die("Utilisateur introuvable !");
            }
            require_once __DIR__ . '/../views/users/edit.php';
        } else {
            die("ID utilisateur manquant !");
        }
    }

    public function store() {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = (int)($_POST['role_id'] ?? 0);
        $password = password_hash("test", PASSWORD_DEFAULT);

        $user = new Utilisateur(0, $nom, $prenom, $email, $password, $role);

        $result = UserDao::createUser($user);

        if ($result['success']) {
            header('Location: index.php?controller=user&action=index');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = (int)($_POST['role_id'] ?? 0);
        $password = trim($_POST['password_user'] ?? '');

        if ($id <= 0) {
            die("ID utilisateur manquant !");
        }

        if ($nom === '' || $prenom === '' || $email === '' || $role <= 0) {
            echo "<p style='color:red;'>Tous les champs sont obligatoires.</p>";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color:red;'>Adresse email invalide.</p>";
            return;
        }

        if ($password !== '') {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $userActuel = UserDao::getUserById($id);
            
            if (!$userActuel) {
                die("Erreur : utilisateur introuvable lors de la mise à jour !");
            }
            
            $password = $userActuel['mot_de_passe'];
        }

        $user = new Utilisateur($id, $nom, $prenom, $email, $password, $role);

        $result = UserDao::updateUser($user);

        if ($result['success']) {
            header('Location: index.php?controller=user&action=index');
            exit;
        } else {
            echo "<p style='color:red;'>{$result['message']}</p>";
        }
    }

    // Archive un utilisateur (au lieu de le supprimer)
    public function archive() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = UserDao::archiveUser($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=user&action=index');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaure un utilisateur archivé
    public function restore() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = UserDao::restoreUser($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=user&action=archived');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Suppression définitive (gardé pour les cas exceptionnels)
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        UserDao::deleteUser($id);
        
        header('Location: index.php?controller=user&action=index');
        exit;
    }

    // Affiche le formulaire d'import CSV
    public function import() {
        require_once __DIR__ . '/../views/users/import.php';
    }

    // Traite le fichier CSV uploadé
    public function importProcess() {
        $errors = [];
        $success = false;
        $message = '';
        $importedCount = 0;

        // Vérifier qu'un fichier a été uploadé
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Aucun fichier n'a été uploadé ou une erreur est survenue.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        $file = $_FILES['csv_file'];

        // Vérifier le type MIME
        $allowedMimes = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes) && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
            $errors[] = "Le fichier doit être au format CSV.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Vérifier la taille (max 2 Mo)
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Le fichier est trop volumineux (max 2 Mo).";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Lire et traiter le CSV
        $csvFile = fopen($file['tmp_name'], 'r');
        
        if ($csvFile === false) {
            $errors[] = "Impossible de lire le fichier CSV.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Lire l'en-tête
        $header = fgetcsv($csvFile, 1000, ',');
        
        if ($header === false || count($header) < 4) {
            $errors[] = "Format d'en-tête invalide. Attendu : nom,prenom,email,role_id";
            fclose($csvFile);
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Nettoyer les en-têtes (enlever les espaces et BOM UTF-8)
        $header = array_map(function($h) {
            return trim(str_replace("\xEF\xBB\xBF", '', $h));
        }, $header);

        // Vérifier que les colonnes nécessaires sont présentes
        $requiredColumns = ['nom', 'prenom', 'email', 'role_id'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                $errors[] = "Colonne manquante : $col";
            }
        }

        if (count($errors) > 0) {
            fclose($csvFile);
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Trouver les indices des colonnes
        $nomIndex = array_search('nom', $header);
        $prenomIndex = array_search('prenom', $header);
        $emailIndex = array_search('email', $header);
        $roleIndex = array_search('role_id', $header);

        $lineNumber = 1; // Commence à 1 car l'en-tête est déjà lu

        // Lire chaque ligne
        while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
            $lineNumber++;

            // Vérifier que la ligne a assez de colonnes
            if (count($data) < 4) {
                $errors[] = "Ligne $lineNumber : nombre de colonnes insuffisant.";
                continue;
            }

            // Récupérer les données
            $nom = trim($data[$nomIndex] ?? '');
            $prenom = trim($data[$prenomIndex] ?? '');
            $email = trim($data[$emailIndex] ?? '');
            $roleId = (int)($data[$roleIndex] ?? 0);

            // Validation
            if (empty($nom)) {
                $errors[] = "Ligne $lineNumber : le nom est obligatoire.";
                continue;
            }

            if (empty($prenom)) {
                $errors[] = "Ligne $lineNumber : le prénom est obligatoire.";
                continue;
            }

            if (empty($email)) {
                $errors[] = "Ligne $lineNumber : l'email est obligatoire.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ligne $lineNumber : format d'email invalide ($email).";
                continue;
            }

            if (!in_array($roleId, [1, 2, 3])) {
                $errors[] = "Ligne $lineNumber : role_id invalide ($roleId). Valeurs autorisées : 1, 2, 3.";
                continue;
            }

            // Créer l'utilisateur
            $password = password_hash("test", PASSWORD_DEFAULT);
            $user = new Utilisateur(0, $nom, $prenom, $email, $password, $roleId);
            
            $result = UserDao::createUser($user);

            if ($result['success']) {
                $importedCount++;
            } else {
                $errors[] = "Ligne $lineNumber ($email) : " . $result['message'];
            }
        }

        fclose($csvFile);

        // Message final
        if ($importedCount > 0) {
            $success = true;
            $message = "$importedCount utilisateur(s) importé(s) avec succès.";
        }

        if (count($errors) === 0 && $importedCount > 0) {
            // Redirection si tout s'est bien passé
            header('Location: index.php?controller=user&action=index');
            exit;
        }

        // Afficher la page avec les résultats
        require_once __DIR__ . '/../views/users/import.php';
    }

    // Télécharger un modèle CSV vide
    public function downloadTemplate() {
        $filename = 'modele_import_utilisateurs.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Ajouter le BOM UTF-8 pour Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // En-têtes
        fputcsv($output, ['nom', 'prenom', 'email', 'role_id'], ',');
        
        // Exemples
        fputcsv($output, ['Dupont', 'Jean', 'jean.dupont@example.com', '1'], ',');
        fputcsv($output, ['Martin', 'Sophie', 'sophie.martin@example.com', '2'], ',');
        fputcsv($output, ['Durand', 'Pierre', 'pierre.durand@example.com', '3'], ',');
        
        fclose($output);
        exit;
    }
        
    // ======================================================
    // MÉTHODE POUR L'EXPORT CSV
    // ======================================================

    /**
     * Exporte uniquement les utilisateurs actifs (non archivés) au format CSV
     */
    public function export() {
        $utilisateurs = UserDao::recupTousLesUtilisateurs();
        $this->generateCsvExport($utilisateurs, 'utilisateurs_actifs');
    }

    /**
     * Génère et télécharge un fichier CSV
     * 
     * @param array $utilisateurs Tableau des utilisateurs à exporter
     * @param string $filePrefix Préfixe du nom de fichier
     */
    private function generateCsvExport($utilisateurs, $filePrefix) {
        // Nom du fichier avec timestamp
        $timestamp = date('Y-m-d_H-i-s');
        $filename = $filePrefix . '_' . $timestamp . '.csv';
        
        // Headers pour le téléchargement
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Ouvrir le flux de sortie
        $output = fopen('php://output', 'w');
        
        // Ajouter le BOM UTF-8 pour une meilleure compatibilité avec Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // En-têtes du CSV
        fputcsv($output, [
            'ID',
            'Nom',
            'Prénom',
            'Email',
            'Rôle ID',
            'Rôle',
            'Statut'
        ], ',');
        
        // Données
        if (is_array($utilisateurs) && count($utilisateurs) > 0) {
            foreach ($utilisateurs as $utilisateur) {
                $statut = (isset($utilisateur['archive']) && $utilisateur['archive'] == 1) ? 'Archivé' : 'Actif';
                
                fputcsv($output, [
                    $utilisateur['id_utilisateur'],
                    $utilisateur['nom'],
                    $utilisateur['prenom'],
                    $utilisateur['email'],
                    $utilisateur['id_role'],
                    $utilisateur['nom_role'],
                    $statut
                ], ',');
            }
        }
        
        fclose($output);
        exit;
    }
    
}

