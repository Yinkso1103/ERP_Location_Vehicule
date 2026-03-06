<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import CSV Scooters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="fas fa-file-csv text-primary"></i> Import CSV — Scooters</h2>
                <a href="index.php?controller=vehicule&action=indexScooter" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle"></i> Erreurs :</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success) && $success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-info-circle text-info"></i> Format attendu</strong>
                </div>
                <div class="card-body">
                    <p class="mb-2">Le fichier CSV doit contenir les colonnes suivantes :</p>
                    <code>marque, modele, prix, annee, couleur, cylindree, coffre</code>
                    <p class="mt-2 text-muted small">La colonne <strong>coffre</strong> doit valoir <code>1</code> (avec coffre) ou <code>0</code> (sans coffre).</p>
                    <div class="mt-2">
                        <a href="index.php?controller=vehicule&action=downloadTemplateScooter" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i> Télécharger le modèle CSV
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="index.php?controller=vehicule&action=importProcessScooter" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Fichier CSV <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Importer
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