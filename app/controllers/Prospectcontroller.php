<?php
require_once __DIR__ . '/../models/ProspectDao.php';

class ProspectController {

    // Liste des prospects actifs
    public function index() {
        $prospects = ProspectDao::recupTousLesProspects();
        require_once __DIR__ . '/../views/prospects/index.php';
    }

    // Liste des prospects archivés
    public function archived() {
        $prospects = ProspectDao::recupProspectsArchives();
        require_once __DIR__ . '/../views/prospects/archived.php';
    }

    // Formulaire de création d'un prospect
    public function create() {
        require_once __DIR__ . '/../views/prospects/create.php';
    }

    // Formulaire d'édition d'un prospect
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $prospect = ProspectDao::getProspectById($id);
            if (!$prospect) {
                die("Prospect introuvable !");
            }
            require_once __DIR__ . '/../views/prospects/edit.php';
        } else {
            die("ID prospect manquant !");
        }
    }

    // Enregistrer un nouveau prospect
    public function store() {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');

        $prospect = new Prospect(0, $nom, $prenom, $email, $telephone, $adresse, $ville, $code_postal);

        $result = ProspectDao::createProspect($prospect);

        if ($result['success']) {
            header('Location: index.php?controller=prospect&action=index');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

    // Mettre à jour un prospect
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');

        if ($id <= 0) {
            die("ID prospect manquant !");
        }

        $prospect = new Prospect($id, $nom, $prenom, $email, $telephone, $adresse, $ville, $code_postal);

        $result = ProspectDao::updateProspect($prospect);

        if ($result['success']) {
            header('Location: index.php?controller=prospect&action=index');
            exit;
        } else {
            echo "<p style='color:red;'>{$result['message']}</p>";
        }
    }

    // Archiver un prospect
    public function archive() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = ProspectDao::archiveProspect($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=prospect&action=index');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaurer un prospect archivé
    public function restore() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = ProspectDao::restoreProspect($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=prospect&action=archived');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Supprimer définitivement un prospect
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        // Vérifier si le prospect a des devis
        if (ProspectDao::prospectHasDevis($id)) {
            die("Impossible de supprimer ce prospect car il a des devis associés.");
        }
        
        ProspectDao::deleteProspect($id);
        
        header('Location: index.php?controller=prospect&action=archived');
        exit;
    }

    // Exporter les prospects en CSV
    public function export() {
        $prospects = ProspectDao::recupTousLesProspects();
        $this->generateCsvExport($prospects, 'prospects_actifs');
    }

    // Génère et télécharge un fichier CSV
    private function generateCsvExport($prospects, $filePrefix) {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = $filePrefix . '_' . $timestamp . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'ID',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Adresse',
            'Ville',
            'Code Postal',
            'Date de création',
            'Statut'
        ], ',');
        
        if (is_array($prospects) && count($prospects) > 0) {
            foreach ($prospects as $prospect) {
                $statut = (isset($prospect['archive']) && $prospect['archive'] == 1) ? 'Archivé' : 'Actif';
                
                fputcsv($output, [
                    $prospect['id_prospect'],
                    $prospect['nom'],
                    $prospect['prenom'],
                    $prospect['email'],
                    $prospect['telephone'],
                    $prospect['adresse'],
                    $prospect['ville'],
                    $prospect['code_postal'],
                    $prospect['date_creation'],
                    $statut
                ], ',');
            }
        }
        
        fclose($output);
        exit;
    }
}
?>