<?php
require_once __DIR__ . '/../models/devisDao.php';
require_once __DIR__ . '/../models/prospectDao.php';
require_once __DIR__ . '/../models/vehiculeDao.php';

class DevisController {

    // Liste de tous les devis
    public function index() {
        $devis = DevisDao::recupTousLesDevis();
        require_once __DIR__ . '/../views/devis/index.php';
    }

    // Liste des devis archivés
    public function archived() {
        $devis = DevisDao::recupDevisArchives();
        require_once __DIR__ . '/../views/devis/archived.php';
    }

    // Formulaire de création d'un devis
    public function create() {
        // Récupérer la liste des prospects actifs
        $prospects = ProspectDao::recupTousLesProspects();
        
        // Récupérer les véhicules disponibles
        $motos = VehiculeDao::recupToutesLesMotos();
        $scooters = VehiculeDao::recupTousLesScooters();
        
        require_once __DIR__ . '/../views/devis/create.php';
    }

    // Afficher les détails d'un devis
    public function view() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $devis = DevisDao::getDevisById($id);
            if (!$devis) {
                die("Devis introuvable !");
            }
            require_once __DIR__ . '/../views/devis/view.php';
        } else {
            die("ID devis manquant !");
        }
    }

    // Formulaire d'édition d'un devis
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $devis = DevisDao::getDevisById($id);
            if (!$devis) {
                die("Devis introuvable !");
            }
            require_once __DIR__ . '/../views/devis/edit.php';
        } else {
            die("ID devis manquant !");
        }
    }

    // Enregistrer un nouveau devis
    public function store() {
        $id_prospect = (int)($_POST['id_prospect'] ?? 0);
        $type_vehicule = trim($_POST['type_vehicule'] ?? '');
        $id_vehicule = (int)($_POST['id_vehicule'] ?? 0);
        $remise = (float)($_POST['remise'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');

        if ($id_prospect <= 0 || $id_vehicule <= 0 || empty($type_vehicule)) {
            echo "<div style='color:red; text-align:center;'>Données manquantes.</div>";
            return;
        }

        // Récupérer les informations du véhicule
        $vehiculeData = null;
        if ($type_vehicule === 'moto') {
            $vehiculeData = VehiculeDao::getMotoById($id_vehicule);
        } elseif ($type_vehicule === 'scooter') {
            $vehiculeData = VehiculeDao::getScooterById($id_vehicule);
        }

        if (!$vehiculeData) {
            die("Véhicule introuvable !");
        }

        // Calculer le prix final
        $prix_vehicule = $vehiculeData['prix'];
        $prix_final = $prix_vehicule - ($prix_vehicule * $remise / 100);

        // Créer le devis
        $devis = new Devis(
            0,
            '',
            $id_prospect,
            $type_vehicule,
            $id_vehicule,
            $vehiculeData['marque'],
            $vehiculeData['modele'],
            $prix_vehicule,
            $remise,
            $prix_final,
            'en_attente',
            null,
            '',
            null,
            $commentaire
        );

        $result = DevisDao::createDevis($devis);

        if ($result['success']) {
            header('Location: index.php?controller=devis&action=index');
            exit;
        } else {
            echo "<div style='color:red; text-align:center;'>{$result['message']}</div>";
        }
    }

    // Mettre à jour un devis
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $remise = (float)($_POST['remise'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');
        $statut = trim($_POST['statut'] ?? 'en_attente');

        if ($id <= 0) {
            die("ID devis manquant !");
        }

        // Récupérer le devis actuel
        $devisActuel = DevisDao::getDevisById($id);
        if (!$devisActuel) {
            die("Devis introuvable !");
        }

        // Calculer le nouveau prix final
        $prix_vehicule = $devisActuel['prix_vehicule'];
        $prix_final = $prix_vehicule - ($prix_vehicule * $remise / 100);

        // Créer l'objet devis avec les nouvelles valeurs
        $devis = new Devis(
            $id,
            $devisActuel['numero_devis'],
            $devisActuel['id_prospect'],
            $devisActuel['type_vehicule'],
            $devisActuel['id_vehicule'],
            $devisActuel['marque_vehicule'],
            $devisActuel['modele_vehicule'],
            $prix_vehicule,
            $remise,
            $prix_final,
            $statut,
            $devisActuel['id_client'],
            $devisActuel['date_creation'],
            $devisActuel['date_validation'],
            $commentaire
        );

        $result = DevisDao::updateDevis($devis);

        if ($result['success']) {
            header('Location: index.php?controller=devis&action=index');
            exit;
        } else {
            echo "<p style='color:red;'>{$result['message']}</p>";
        }
    }

    // 🔥 VALIDER UN DEVIS ET CONVERTIR LE PROSPECT EN CLIENT
    public function valider() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }

        $result = DevisDao::validerDevis($id);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            header('Location: index.php?controller=devis&action=index');
        } else {
            die("Erreur lors de la validation : " . $result['message']);
        }
        exit;
    }

    // Refuser un devis
    public function refuser() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }

        $result = DevisDao::refuserDevis($id);

        if ($result['success']) {
            header('Location: index.php?controller=devis&action=index');
        } else {
            die("Erreur : " . $result['message']);
        }
        exit;
    }

    // Archiver un devis
    public function archive() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = DevisDao::archiveDevis($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=devis&action=index');
        } else {
            die("Erreur lors de l'archivage : " . $result['message']);
        }
        exit;
    }

    // Restaurer un devis
    public function restore() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        $result = DevisDao::restoreDevis($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=devis&action=archived');
        } else {
            die("Erreur lors de la restauration : " . $result['message']);
        }
        exit;
    }

    // Supprimer définitivement un devis
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }
        
        DevisDao::deleteDevis($id);
        
        header('Location: index.php?controller=devis&action=archived');
        exit;
    }

    // Générer un PDF du devis
    public function generatePDF() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            die("ID invalide");
        }

        $devis = DevisDao::getDevisById($id);
        if (!$devis) {
            die("Devis introuvable !");
        }

        // Ici, tu peux implémenter la génération de PDF avec une bibliothèque comme TCPDF ou mPDF
        // Pour l'instant, on redirige vers la vue
        require_once __DIR__ . '/../views/devis/pdf.php';
    }
}
?>