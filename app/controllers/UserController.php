<?php
require_once __DIR__ . '/../models/UserDao.php';

class UserController {

    // Vérifie que l'utilisateur est connecté ET admin (id_role = 1) → sinon redirige
    private function checkAdminAccess() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=auth&action=index');
            exit;
        }
        if ($_SESSION['utilisateur']['id_role'] != 1) {
            header('Location: index.php?controller=auth&action=index');
            exit;
        }
    }

    // Liste des utilisateurs actifs (visible par tous les connectés)
    public function index() {
    $users = UserDao::recupTousLesUtilisateurs();
    require_once __DIR__ . '/../views/users/index.php';
}

    // Liste des utilisateurs archivés (admin seulement)
    public function archived() {
        $this->checkAdminAccess();
        $users = UserDao::recupUtilisateursArchives();
        require_once __DIR__ . '/../views/users/archived.php';
    }

    // Formulaire création (admin seulement)

    public function create() {
        $this->checkAdminAccess();
        require_once __DIR__ . '/../views/users/create.php';
    }
        
    // Charge un utilisateur pour édition (admin seulement)
    public function edit() {
        $this->checkAdminAccess();
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

        // Crée un utilisateur (mot de passe hashé avec bcrypt via password_hash)
    public function store() {
        $this->checkAdminAccess();
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $role     = (int)($_POST['role_id'] ?? 0);
        $password = password_hash(trim($_POST['password_user'] ?? 'changement'), PASSWORD_DEFAULT);

        $user = new Utilisateur(0, $nom, $prenom, $email, $password, $role);

        $result = UserDao::createUser($user);

        if ($result['success']) {
            header('Location: index.php?controller=user&action=index');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

        // Met à jour un utilisateur (re-hash le mdp seulement si un nouveau est saisi)
    public function update() {
        $this->checkAdminAccess();
        $id       = (int)($_GET['id'] ?? 0);
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $role     = (int)($_POST['role_id'] ?? 0);
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
        // Si champ mdp vide → conserve l'ancien hash en BDD
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

    // Soft delete (archive = 1) → empêche de s'auto-archiver
    public function archive() {
        $this->checkAdminAccess();
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            die("ID invalide");
        }

        // Protection : un admin ne peut pas désactiver son propre compte
        if ($id === (int)$_SESSION['utilisateur']['id_utilisateur']) {
            die("Vous ne pouvez pas archiver votre propre compte.");
        }

        $result = UserDao::archiveUser($id);

        if ($result['success']) {
            header('Location: index.php?controller=user&action=index');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restauration (archive = 0)
    public function restore() {
        $this->checkAdminAccess();
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

    // Hard delete → empêche aussi de se supprimer soi-même
    public function delete() {
        $this->checkAdminAccess();
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            die("ID invalide");
        }

        // Protection : un admin ne peut pas se supprimer lui-même
        if ($id === (int)$_SESSION['utilisateur']['id_utilisateur']) {
            die("Vous ne pouvez pas supprimer votre propre compte.");
        }

        UserDao::deleteUser($id);

        header('Location: index.php?controller=user&action=index');
        exit;
    }

    // Affiche le formulaire d'import CSV
    public function import() {
        $this->checkAdminAccess();
        require_once __DIR__ . '/../views/users/import.php';
    }

    // Traite le fichier CSV uploadé ligne par ligne
    public function importProcess() {
        $this->checkAdminAccess();
        $errors = [];
        $success = false;
        $message = '';
        $importedCount = 0;

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Aucun fichier n'a été uploadé ou une erreur est survenue.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        $file = $_FILES['csv_file'];
        
        // Vérifie le type MIME réel du fichier (pas juste l'extension)
        $allowedMimes = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes) && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
            $errors[] = "Le fichier doit être au format CSV.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        if ($file['size'] > 2 * 1024 * 1024) { // max 2 Mo
            $errors[] = "Le fichier est trop volumineux (max 2 Mo).";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        $csvFile = fopen($file['tmp_name'], 'r');

        if ($csvFile === false) {
            $errors[] = "Impossible de lire le fichier CSV.";
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Lit la première ligne = en-têtes de colonnes
        $header = fgetcsv($csvFile, 1000, ',');
        if ($header === false || count($header) < 4) {
            $errors[] = "Format d'en-tête invalide. Attendu : nom,prenom,email,role_id";
            fclose($csvFile);
            require_once __DIR__ . '/../views/users/import.php';
            return;
        }

        // Supprime le BOM UTF-8 éventuel (présent si exporté depuis Excel)
        $header = array_map(function($h) {
            return trim(str_replace("\xEF\xBB\xBF", '', $h));
        }, $header);

        // Vérifie que toutes les colonnes obligatoires sont présentes
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

        // Récupère l'index de chaque colonne
        $nomIndex    = array_search('nom', $header);
        $prenomIndex = array_search('prenom', $header);
        $emailIndex  = array_search('email', $header);
        $roleIndex   = array_search('role_id', $header);

        $lineNumber = 1; // commence à 1 car la ligne 1 est l'en-tête


        while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
            $lineNumber++;

            if (count($data) < 4) {
                $errors[] = "Ligne $lineNumber : nombre de colonnes insuffisant.";
                continue;
            }

            $nom    = trim($data[$nomIndex] ?? '');
            $prenom = trim($data[$prenomIndex] ?? '');
            $email  = trim($data[$emailIndex] ?? '');
            $roleId = (int)($data[$roleIndex] ?? 0);

            if (empty($nom))    { $errors[] = "Ligne $lineNumber : le nom est obligatoire.";    continue; }
            if (empty($prenom)) { $errors[] = "Ligne $lineNumber : le prénom est obligatoire."; continue; }
            if (empty($email))  { $errors[] = "Ligne $lineNumber : l'email est obligatoire.";   continue; }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ligne $lineNumber : format d'email invalide ($email).";
                continue;
            }

            if (!in_array($roleId, [1, 2, 3])) {
                $errors[] = "Ligne $lineNumber : role_id invalide ($roleId). Valeurs autorisées : 1, 2, 3.";
                continue;
            }

            // Mot de passe par défaut "changeme" (hashé), l'user devra le changer
            $password = password_hash("changeme", PASSWORD_DEFAULT);
            $user = new Utilisateur(0, $nom, $prenom, $email, $password, $roleId);

            $result = UserDao::createUser($user);

            if ($result['success']) {
                $importedCount++;
            } else {
                $errors[] = "Ligne $lineNumber ($email) : " . $result['message'];
            }
        }

        fclose($csvFile);

        if ($importedCount > 0) {
            $success = true;
            $message = "$importedCount utilisateur(s) importé(s) avec succès.";
        }

        if (count($errors) === 0 && $importedCount > 0) {
            header('Location: index.php?controller=user&action=index');
            exit;
        }

        require_once __DIR__ . '/../views/users/import.php';
    }

    // Télécharger un modèle CSV vide
    public function downloadTemplate() {
        $filename = 'modele_import_utilisateurs.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['nom', 'prenom', 'email', 'role_id'], ',');
        fputcsv($output, ['Dupont', 'Jean', 'jean.dupont@example.com', '1'], ',');
        fputcsv($output, ['Martin', 'Sophie', 'sophie.martin@example.com', '2'], ',');
        fputcsv($output, ['Durand', 'Pierre', 'pierre.durand@example.com', '3'], ',');

        fclose($output);
        exit;
    }

      
    // Exporte les utilisateurs actifs en CSV
    public function export() {
        $this->checkAdminAccess();
        $utilisateurs = UserDao::recupTousLesUtilisateurs();
        $this->generateCsvExport($utilisateurs, 'utilisateurs_actifs');
    }

    private function generateCsvExport($utilisateurs, $filePrefix) {
        $timestamp = date('Y-m-d_H-i-s');
        $filename  = $filePrefix . '_' . $timestamp . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['ID', 'Nom', 'Prénom', 'Email', 'Rôle ID', 'Rôle', 'Statut'], ',');

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