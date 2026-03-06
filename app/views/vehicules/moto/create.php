<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Moto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="fas fa-plus-circle text-success"></i> Nouvelle Moto</h2>
                <a href="index.php?controller=vehicule&action=indexMoto" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="index.php?controller=vehicule&action=storeMoto" method="POST">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Marque <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="marque" required
                                       placeholder="Honda, Yamaha...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modèle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="modele" required
                                       placeholder="CBR600RR, MT-07...">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type de moto <span class="text-danger">*</span></label>
                                <select class="form-select" name="type_moto" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="Sportive">Sportive</option>
                                    <option value="Roadster">Roadster</option>
                                    <option value="Trail">Trail</option>
                                    <option value="Custom">Custom</option>
                                    <option value="Touring">Touring</option>
                                    <option value="Enduro">Enduro</option>
                                    <option value="Cross">Cross</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cylindrée (cc) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="cylindree" required
                                       min="50" max="2000" placeholder="600">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Année <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="annee" required
                                       min="1990" max="<?= date('Y') + 1 ?>" placeholder="<?= date('Y') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Couleur</label>
                                <input type="text" class="form-control" name="couleur"
                                       placeholder="Rouge, Noir...">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Prix (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="prix" required
                                           min="0" step="0.01" placeholder="8500.00">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Enregistrer la moto
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>