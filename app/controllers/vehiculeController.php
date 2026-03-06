<?php
require_once __DIR__ . '/../models/vehiculeDao.php';

class VehiculeController {

    // ===== MOTOS =====
    
    // Liste des motos actives
    public function indexMoto() {
        $motos = VehiculeDao::recupToutesLesMotos();
        require_once __DIR__ . '/../views/vehicules/moto/index.php';
    }

    // Liste des motos archivées
    public function archivedMoto() {
        $motos = VehiculeDao::recupMotosArchivees();
        require_once __DIR__ . '/../views/vehicules/moto/archived.php';
    }

    // Formulaire de création d'une moto
    public function createMoto() {
        require_once __DIR__ . '/../views/vehicules/moto/create.php';
    }

    // Formulaire d'édition d'une moto
    public function editMoto() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $moto = VehiculeDao::getMotoById($id);
            if (!$moto) {
                die("Moto introuvable !");
            }
            require_once __DIR__ . '/../views/vehicules/moto/edit.php';
        } else {
            die("ID moto manquant !");
        }
    }

    // Enregistrer une nouvelle moto
    public function storeMoto() {
        $marque = trim($_POST['marque'] ?? '');
        $modele = trim($_POST['modele'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0);
        $annee = (int)($_POST['annee'] ?? 0);
        $couleur = trim($_POST['couleur'] ?? '');
        $cylindree = (int)($_POST['cylindree'] ?? 0);
        $type_moto = trim($_POST['type_moto'] ?? '');

        $moto = new Moto(0, $marque, $modele, $prix, $annee, $couleur, $cylindree, $type_moto);
        $result = VehiculeDao::createMoto($moto);

        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexMoto');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

    // Mettre à jour une moto
    public function updateMoto() {
        $id = (int)($_GET['id'] ?? 0);
        $marque = trim($_POST['marque'] ?? '');
        $modele = trim($_POST['modele'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0);
        $annee = (int)($_POST['annee'] ?? 0);
        $couleur = trim($_POST['couleur'] ?? '');
        $cylindree = (int)($_POST['cylindree'] ?? 0);
        $type_moto = trim($_POST['type_moto'] ?? '');

        if ($id <= 0) {
            die("ID moto manquant !");
        }

        $moto = new Moto($id, $marque, $modele, $prix, $annee, $couleur, $cylindree, $type_moto);
        $result = VehiculeDao::updateMoto($moto);

        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexMoto');
            exit;
        } else {
            echo "<p style='color:red;'>{$result['message']}</p>";
        }
    }

    // Archiver une moto
    public function archiveMoto() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = VehiculeDao::archiveMoto($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexMoto');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaurer une moto
    public function restoreMoto() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = VehiculeDao::restoreMoto($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=archivedMoto');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Supprimer définitivement une moto
    public function deleteMoto() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        VehiculeDao::deleteMoto($id);
        header('Location: index.php?controller=vehicule&action=archivedMoto');
        exit;
    }

    // ===== SCOOTERS =====
    
    // Liste des scooters actifs
    public function indexScooter() {
        $scooters = VehiculeDao::recupTousLesScooters();
        require_once __DIR__ . '/../views/vehicules/scooter/index.php';
    }

    // Liste des scooters archivés
    public function archivedScooter() {
        $scooters = VehiculeDao::recupScootersArchives();
        require_once __DIR__ . '/../views/vehicules/scooter/archived.php';
    }

    // Formulaire de création d'un scooter
    public function createScooter() {
        require_once __DIR__ . '/../views/vehicules/scooter/create.php';
    }

    // Formulaire d'édition d'un scooter
    public function editScooter() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $scooter = VehiculeDao::getScooterById($id);
            if (!$scooter) {
                die("Scooter introuvable !");
            }
            require_once __DIR__ . '/../views/vehicules/scooter/edit.php';
        } else {
            die("ID scooter manquant !");
        }
    }

    // Enregistrer un nouveau scooter
    public function storeScooter() {
        $marque = trim($_POST['marque'] ?? '');
        $modele = trim($_POST['modele'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0);
        $annee = (int)($_POST['annee'] ?? 0);
        $couleur = trim($_POST['couleur'] ?? '');
        $cylindree = (int)($_POST['cylindree'] ?? 0);
        $coffre = isset($_POST['coffre']) ? 1 : 0;

        $scooter = new Scooter(0, $marque, $modele, $prix, $annee, $couleur, $cylindree, $coffre);
        $result = VehiculeDao::createScooter($scooter);

        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexScooter');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

    // Mettre à jour un scooter
    public function updateScooter() {
        $id = (int)($_GET['id'] ?? 0);
        $marque = trim($_POST['marque'] ?? '');
        $modele = trim($_POST['modele'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0);
        $annee = (int)($_POST['annee'] ?? 0);
        $couleur = trim($_POST['couleur'] ?? '');
        $cylindree = (int)($_POST['cylindree'] ?? 0);
        $coffre = isset($_POST['coffre']) ? 1 : 0;

        if ($id <= 0) {
            die("ID scooter manquant !");
        }

        $scooter = new Scooter($id, $marque, $modele, $prix, $annee, $couleur, $cylindree, $coffre);
        $result = VehiculeDao::updateScooter($scooter);

        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexScooter');
            exit;
        } else {
            echo "<p style='color:red;'>{$result['message']}</p>";
        }
    }

    // Archiver un scooter
    public function archiveScooter() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = VehiculeDao::archiveScooter($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=indexScooter');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaurer un scooter
    public function restoreScooter() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = VehiculeDao::restoreScooter($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=vehicule&action=archivedScooter');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Supprimer définitivement un scooter
    public function deleteScooter() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die("ID invalide");
        }
        
        VehiculeDao::deleteScooter($id);
        header('Location: index.php?controller=vehicule&action=archivedScooter');
        exit;
    }

    // ===== IMPORT CSV =====
    
    // Page d'import pour motos
    public function importMoto() {
        require_once __DIR__ . '/../views/vehicules/moto/import.php';
    }

    // Page d'import pour scooters
    public function importScooter() {
        require_once __DIR__ . '/../views/vehicules/scooter/import.php';
    }

    // Traiter l'import CSV pour motos
    public function importProcessMoto() {
        $errors = [];
        $success = false;
        $message = '';
        $importedCount = 0;

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Aucun fichier n'a été uploadé ou une erreur est survenue.";
            require_once __DIR__ . '/../views/vehicules/moto/import.php';
            return;
        }

        $file = $_FILES['csv_file'];
        $csvFile = fopen($file['tmp_name'], 'r');
        
        if ($csvFile === false) {
            $errors[] = "Impossible de lire le fichier CSV.";
            require_once __DIR__ . '/../views/vehicules/moto/import.php';
            return;
        }

        $header = fgetcsv($csvFile, 1000, ',');
        $header = array_map(function($h) {
            return trim(str_replace("\xEF\xBB\xBF", '', $h));
        }, $header);

        $requiredColumns = ['marque', 'modele', 'prix', 'annee', 'couleur', 'cylindree', 'type_moto'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                $errors[] = "Colonne manquante : $col";
            }
        }

        if (count($errors) > 0) {
            fclose($csvFile);
            require_once __DIR__ . '/../views/vehicules/moto/import.php';
            return;
        }

        $lineNumber = 1;
        while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
            $lineNumber++;
            
            $marque = trim($data[array_search('marque', $header)] ?? '');
            $modele = trim($data[array_search('modele', $header)] ?? '');
            $prix = (float)($data[array_search('prix', $header)] ?? 0);
            $annee = (int)($data[array_search('annee', $header)] ?? 0);
            $couleur = trim($data[array_search('couleur', $header)] ?? '');
            $cylindree = (int)($data[array_search('cylindree', $header)] ?? 0);
            $type_moto = trim($data[array_search('type_moto', $header)] ?? '');

            if (empty($marque) || empty($modele) || $prix <= 0) {
                $errors[] = "Ligne $lineNumber : données invalides.";
                continue;
            }

            $moto = new Moto(0, $marque, $modele, $prix, $annee, $couleur, $cylindree, $type_moto);
            $result = VehiculeDao::createMoto($moto);

            if ($result['success']) {
                $importedCount++;
            } else {
                $errors[] = "Ligne $lineNumber : " . $result['message'];
            }
        }

        fclose($csvFile);

        if ($importedCount > 0) {
            $success = true;
            $message = "$importedCount moto(s) importée(s) avec succès.";
        }

        if (count($errors) === 0 && $importedCount > 0) {
            header('Location: index.php?controller=vehicule&action=indexMoto');
            exit;
        }

        require_once __DIR__ . '/../views/vehicules/moto/import.php';
    }

    // Traiter l'import CSV pour scooters
    public function importProcessScooter() {
        $errors = [];
        $success = false;
        $message = '';
        $importedCount = 0;

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Aucun fichier n'a été uploadé ou une erreur est survenue.";
            require_once __DIR__ . '/../views/vehicules/scooter/import.php';
            return;
        }

        $file = $_FILES['csv_file'];
        $csvFile = fopen($file['tmp_name'], 'r');
        
        if ($csvFile === false) {
            $errors[] = "Impossible de lire le fichier CSV.";
            require_once __DIR__ . '/../views/vehicules/scooter/import.php';
            return;
        }

        $header = fgetcsv($csvFile, 1000, ',');
        $header = array_map(function($h) {
            return trim(str_replace("\xEF\xBB\xBF", '', $h));
        }, $header);

        $requiredColumns = ['marque', 'modele', 'prix', 'annee', 'couleur', 'cylindree', 'coffre'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                $errors[] = "Colonne manquante : $col";
            }
        }

        if (count($errors) > 0) {
            fclose($csvFile);
            require_once __DIR__ . '/../views/vehicules/scooter/import.php';
            return;
        }

        $lineNumber = 1;
        while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
            $lineNumber++;
            
            $marque = trim($data[array_search('marque', $header)] ?? '');
            $modele = trim($data[array_search('modele', $header)] ?? '');
            $prix = (float)($data[array_search('prix', $header)] ?? 0);
            $annee = (int)($data[array_search('annee', $header)] ?? 0);
            $couleur = trim($data[array_search('couleur', $header)] ?? '');
            $cylindree = (int)($data[array_search('cylindree', $header)] ?? 0);
            $coffre = (int)($data[array_search('coffre', $header)] ?? 0);

            if (empty($marque) || empty($modele) || $prix <= 0) {
                $errors[] = "Ligne $lineNumber : données invalides.";
                continue;
            }

            $scooter = new Scooter(0, $marque, $modele, $prix, $annee, $couleur, $cylindree, $coffre);
            $result = VehiculeDao::createScooter($scooter);

            if ($result['success']) {
                $importedCount++;
            } else {
                $errors[] = "Ligne $lineNumber : " . $result['message'];
            }
        }

        fclose($csvFile);

        if ($importedCount > 0) {
            $success = true;
            $message = "$importedCount scooter(s) importé(s) avec succès.";
        }

        if (count($errors) === 0 && $importedCount > 0) {
            header('Location: index.php?controller=vehicule&action=indexScooter');
            exit;
        }

        require_once __DIR__ . '/../views/vehicules/scooter/import.php';
    }

    public function index() {
    header('Location: index.php?controller=vehicule&action=indexMoto');
    exit;
}
    // Télécharger un modèle CSV pour motos
    public function downloadTemplateMoto() {
        $filename = 'modele_import_motos.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['marque', 'modele', 'prix', 'annee', 'couleur', 'cylindree', 'type_moto'], ',');
        fputcsv($output, ['Honda', 'CBR600RR', '12000', '2023', 'Rouge', '600', 'Sportive'], ',');
        fputcsv($output, ['Yamaha', 'MT-07', '8000', '2022', 'Noir', '700', 'Roadster'], ',');
        
        fclose($output);
        exit;
    }

    // Télécharger un modèle CSV pour scooters
    public function downloadTemplateScooter() {
        $filename = 'modele_import_scooters.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['marque', 'modele', 'prix', 'annee', 'couleur', 'cylindree', 'coffre'], ',');
        fputcsv($output, ['Piaggio', 'Liberty 125', '3500', '2023', 'Blanc', '125', '1'], ',');
        fputcsv($output, ['Peugeot', 'Tweet 50', '2200', '2022', 'Noir', '50', '1'], ',');
        
        fclose($output);
        exit;
    }
}
?>