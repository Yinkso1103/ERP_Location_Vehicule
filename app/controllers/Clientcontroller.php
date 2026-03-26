<?php
require_once __DIR__ . '/../models/ClientDao.php';

class ClientController {

    // Liste des clients actifs
    public function index() {
        $clients = ClientDao::recupTousLesClients();
        require_once __DIR__ . '/../views/clients/index.php';
    }

    // Liste des clients archivés
    public function archived() {
        $clients = ClientDao::recupClientsArchives();
        require_once __DIR__ . '/../views/clients/archived.php';
    }

    // Afficher les détails d'un client
    public function view() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $client = ClientDao::getClientById($id);
            if (!$client) {
                die("Client introuvable !");
            }
            
            // Récupérer les devis du client
            require_once __DIR__ . '/../models/DevisDao.php';
            $devis = DevisDao::getDevisByProspectId($client['id_prospect']);
            
            require_once __DIR__ . '/../views/clients/view.php';
        } else {
            die("ID client manquant !");
        }
    }

    // Archiver un client
    public function archive() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = ClientDao::archiveClient($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=client&action=index');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaurer un client
    public function restore() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = ClientDao::restoreClient($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=client&action=archived');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Exporter les clients en CSV
    public function export() {
        $clients = ClientDao::recupTousLesClients();
        $this->generateCsvExport($clients, 'clients_actifs');
    }

    // Génère et télécharge un fichier CSV
    private function generateCsvExport($clients, $filePrefix) {
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
            'Numéro Client',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Adresse',
            'Ville',
            'Code Postal',
            'Date de conversion',
            'Statut'
        ], ',');
        
        if (is_array($clients) && count($clients) > 0) {
            foreach ($clients as $client) {
                $statut = (isset($client['archive']) && $client['archive'] == 1) ? 'Archivé' : 'Actif';
                
                fputcsv($output, [
                    $client['id_client'],
                    $client['numero_client'],
                    $client['nom'],
                    $client['prenom'],
                    $client['email'],
                    $client['telephone'],
                    $client['adresse'],
                    $client['ville'],
                    $client['code_postal'],
                    $client['date_conversion'],
                    $statut
                ], ',');
            }
        }
        
        fclose($output);
        exit;
    }
}
?>