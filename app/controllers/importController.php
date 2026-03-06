<?php
require_once __DIR__ . '/../config/db.php';

class ImportController {

    public function index(){
        require_once __DIR__ . '/../views/import/import.php';
    }

    public function import(){
        $errors = [];
        $message = '';
        $nbLignesImportees = 0;

        // Vérifier que c'est une requête POST avec un fichier
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['csv_file'])) {
            $errors[] = "Aucun fichier n'a été envoyé.";
            require_once __DIR__ . '/../views/import/import.php';
            return;
        }

        $fichier = $_FILES['csv_file'];

        // Vérifier les erreurs d'upload
        if($fichier['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors de l'upload du fichier.";
            require_once __DIR__ . '/../views/import/import.php';
            return;
        }

        // Récupérer la table cible
        $tableCible = $_POST['table_cible'] ?? 'utilisateur';

        // Ouvrir le fichier CSV
        $handle = fopen($fichier['tmp_name'], 'r');
        
        if($handle === false) {
            $errors[] = "Impossible d'ouvrir le fichier.";
            require_once __DIR__ . '/../views/import/import.php';
            return;
        }

        // Lire l'en-tête (première ligne)
        $entete = fgetcsv($handle, 1000, ',');
        
        if($entete === false) {
            $errors[] = "Le fichier est vide.";
            fclose($handle);
            require_once __DIR__ . '/../views/import/import.php';
            return;
        }

        // Nettoyer les noms de colonnes (enlever espaces et BOM UTF-8)
        $colonnes = array_map(function($col) {
            return trim(str_replace("\xEF\xBB\xBF", '', $col));
        }, $entete);

        $nbColonnes = count($colonnes);

        // Préparer la requête SQL
        $placeholders = rtrim(str_repeat('?,', $nbColonnes), ',');
        $sql = "INSERT INTO $tableCible (" . implode(',', $colonnes) . ") VALUES ($placeholders)";

        $pdo = Db::seConnecterBdd();
        $numeroLigne = 1; // Ligne d'en-tête

        // Lire chaque ligne du CSV
        while(($ligne = fgetcsv($handle, 1000, ',')) !== false) {
            $numeroLigne++;

            // Vérifier que la ligne a le bon nombre de colonnes
            if(count($ligne) !== $nbColonnes) {
                $errors[] = "Ligne $numeroLigne : nombre de colonnes incorrect.";
                continue;
            }

            // Nettoyer les valeurs
            $valeurs = array_map('trim', $ligne);

            // Insérer dans la base de données
            try {
                $stmt = $pdo->prepare($sql);
                foreach($valeurs as $index => $valeur) {
                    $stmt->bindValue($index + 1, $valeur);
                }
                $stmt->execute();
                $nbLignesImportees++;
            } catch (PDOException $e) {
                $errors[] = "Ligne $numeroLigne : erreur d'insertion - " . $e->getMessage();
                error_log("Erreur ligne $numeroLigne: " . $e->getMessage());
            }
        }

        // IMPORTANT : Fermer le fichier
        fclose($handle);

        // Message de succès
        if($nbLignesImportees > 0) {
            $message = "$nbLignesImportees ligne(s) importée(s) avec succès dans '$tableCible'.";
        }

        // Si tout s'est bien passé, rediriger
        if(count($errors) === 0 && $nbLignesImportees > 0) {
            $_SESSION['success_message'] = $message;
            header('Location: index.php?controller=import&action=index');
            exit;
        }

        // Afficher la page avec les résultats
        require_once __DIR__ . '/../views/import/import.php';
    }

    /**
     * Télécharger un modèle CSV
     */
    public function downloadTemplate() {
        $table = $_POST['table'] ?? $_GET['table'] ?? 'utilisateur';
        $filename = "modele_import_$table.csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM UTF-8 pour Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Exemples selon la table
        if($table === 'utilisateur') {
            fputcsv($output, ['nom', 'prenom', 'email', 'mot_de_passe', 'id_role', 'archive'], ',');
            fputcsv($output, ['Dupont', 'Jean', 'jean.dupont@example.com', 'test123', '1', '0'], ',');
            fputcsv($output, ['Martin', 'Sophie', 'sophie.martin@example.com', 'test456', '2', '0'], ',');
        } elseif($table === 'role') {
            fputcsv($output, ['nom_role'], ',');
            fputcsv($output, ['Admin'], ',');
            fputcsv($output, ['RH'], ',');
        } else {
            // Modèle générique
            fputcsv($output, ['colonne1', 'colonne2', 'colonne3'], ',');
            fputcsv($output, ['valeur1', 'valeur2', 'valeur3'], ',');
        }
        
        fclose($output);
        exit;
    }
}