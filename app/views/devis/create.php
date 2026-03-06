<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Devis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Créer un nouveau devis</h3>
                    </div>
                    <div class="card-body">
                        <form action="index.php?controller=devis&action=store" method="POST" id="devisForm">
                            
                            <!-- Sélection du prospect -->
                            <div class="mb-4">
                                <h5>1. Sélectionner un prospect</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="id_prospect" class="form-label">Prospect *</label>
                                    <select class="form-select" id="id_prospect" name="id_prospect" required>
                                        <option value="">-- Choisir un prospect --</option>
                                        <?php if (isset($prospects) && is_array($prospects)): ?>
                                            <?php foreach($prospects as $p): ?>
                                                <option value="<?= $p['id_prospect'] ?>" <?= (isset($_GET['prospect']) && $_GET['prospect'] == $p['id_prospect']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($p['nom']) ?> <?= htmlspecialchars($p['prenom']) ?> 
                                                    (<?= htmlspecialchars($p['email']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Sélection du type de véhicule -->
                            <div class="mb-4">
                                <h5>2. Choisir le type de véhicule</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="type_vehicule" class="form-label">Type de véhicule *</label>
                                    <select class="form-select" id="type_vehicule" name="type_vehicule" required onchange="afficherVehicules()">
                                        <option value="">-- Choisir un type --</option>
                                        <option value="moto">Moto</option>
                                        <option value="scooter">Scooter</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Sélection du véhicule (moto ou scooter) -->
                            <div class="mb-4" id="vehicule_section" style="display: none;">
                                <h5>3. Sélectionner un véhicule</h5>
                                <hr>
                                
                                <!-- Motos -->
                                <div class="mb-3" id="moto_select" style="display: none;">
                                    <label for="id_moto" class="form-label">Moto *</label>
                                    <select class="form-select" id="id_moto" name="id_vehicule" onchange="afficherPrix('moto')">
                                        <option value="">-- Choisir une moto --</option>
                                        <?php if (isset($motos) && is_array($motos)): ?>
                                            <?php foreach($motos as $m): ?>
                                                <option value="<?= $m['id_moto'] ?>" 
                                                        data-prix="<?= $m['prix'] ?>"
                                                        data-marque="<?= htmlspecialchars($m['marque']) ?>"
                                                        data-modele="<?= htmlspecialchars($m['modele']) ?>">
                                                    <?= htmlspecialchars($m['marque']) ?> <?= htmlspecialchars($m['modele']) ?> 
                                                    - <?= number_format($m['prix'], 2, ',', ' ') ?> € 
                                                    (<?= $m['cylindree'] ?>cc)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Scooters -->
                                <div class="mb-3" id="scooter_select" style="display: none;">
                                    <label for="id_scooter" class="form-label">Scooter *</label>
                                    <select class="form-select" id="id_scooter" name="id_vehicule" onchange="afficherPrix('scooter')">
                                        <option value="">-- Choisir un scooter --</option>
                                        <?php if (isset($scooters) && is_array($scooters)): ?>
                                            <?php foreach($scooters as $s): ?>
                                                <option value="<?= $s['id_scooter'] ?>" 
                                                        data-prix="<?= $s['prix'] ?>"
                                                        data-marque="<?= htmlspecialchars($s['marque']) ?>"
                                                        data-modele="<?= htmlspecialchars($s['modele']) ?>">
                                                    <?= htmlspecialchars($s['marque']) ?> <?= htmlspecialchars($s['modele']) ?> 
                                                    - <?= number_format($s['prix'], 2, ',', ' ') ?> € 
                                                    (<?= $s['cylindree'] ?>cc)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Prix et remise -->
                            <div class="mb-4" id="prix_section" style="display: none;">
                                <h5>4. Prix et remise</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="prix_affiche" class="form-label">Prix du véhicule</label>
                                        <input type="text" class="form-control" id="prix_affiche" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="remise" class="form-label">Remise (%)</label>
                                        <input type="number" class="form-control" id="remise" name="remise" 
                                               min="0" max="100" step="0.01" value="0" onchange="calculerPrixFinal()">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="prix_final_affiche" class="form-label"><strong>Prix Final</strong></label>
                                        <input type="text" class="form-control fw-bold" id="prix_final_affiche" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="commentaire" class="form-label">Commentaire / Notes</label>
                                    <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                                </div>
                            </div>

                            <hr>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?controller=devis&action=index" class="btn btn-secondary">
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Créer le devis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function afficherVehicules() {
            const type = document.getElementById('type_vehicule').value;
            const motoSelect = document.getElementById('moto_select');
            const scooterSelect = document.getElementById('scooter_select');
            const vehiculeSection = document.getElementById('vehicule_section');
            const prixSection = document.getElementById('prix_section');

            // Réinitialiser les sélections
            document.getElementById('id_moto').value = '';
            document.getElementById('id_scooter').value = '';
            prixSection.style.display = 'none';

            if (type === 'moto') {
                vehiculeSection.style.display = 'block';
                motoSelect.style.display = 'block';
                scooterSelect.style.display = 'none';
                document.getElementById('id_moto').required = true;
                document.getElementById('id_scooter').required = false;
            } else if (type === 'scooter') {
                vehiculeSection.style.display = 'block';
                scooterSelect.style.display = 'block';
                motoSelect.style.display = 'none';
                document.getElementById('id_scooter').required = true;
                document.getElementById('id_moto').required = false;
            } else {
                vehiculeSection.style.display = 'none';
            }
        }

        function afficherPrix(type) {
            let selectElement;
            if (type === 'moto') {
                selectElement = document.getElementById('id_moto');
            } else {
                selectElement = document.getElementById('id_scooter');
            }

            const selectedOption = selectElement.options[selectElement.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const prix = parseFloat(selectedOption.getAttribute('data-prix'));
                document.getElementById('prix_affiche').value = prix.toFixed(2) + ' €';
                document.getElementById('prix_section').style.display = 'block';
                calculerPrixFinal();
            }
        }

        function calculerPrixFinal() {
            const prixText = document.getElementById('prix_affiche').value;
            const prix = parseFloat(prixText.replace(' €', ''));
            const remise = parseFloat(document.getElementById('remise').value) || 0;
            
            const prixFinal = prix - (prix * remise / 100);
            document.getElementById('prix_final_affiche').value = prixFinal.toFixed(2) + ' €';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
    </div>

</body>
</html>