<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Moto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="fas fa-edit text-primary"></i> Modifier la Moto</h2>
                <a href="index.php?controller=vehicule&action=indexMoto" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="index.php?controller=vehicule&action=updateMoto&id=<?= $moto['id_moto'] ?>" method="POST">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Marque <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="marque" required
                                       value="<?= htmlspecialchars($moto['marque']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modèle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="modele" required
                                       value="<?= htmlspecialchars($moto['modele']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type de moto <span class="text-danger">*</span></label>
                                <select class="form-select" name="type_moto" required>
                                    <?php foreach (['Sportive','Roadster','Trail','Custom','Touring','Enduro','Cross'] as $type): ?>
                                        <option value="<?= $type ?>" <?= $moto['type_moto'] === $type ? 'selected' : '' ?>>
                                            <?= $type ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cylindrée (cc) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="cylindree" required
                                       min="50" max="2000" value="<?= htmlspecialchars($moto['cylindree']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Année <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="annee" required
                                       min="1990" max="<?= date('Y') + 1 ?>" value="<?= htmlspecialchars($moto['annee']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Couleur</label>
                                <input type="text" class="form-control" name="couleur"
                                       value="<?= htmlspecialchars($moto['couleur']) ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Prix (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="prix" required
                                           min="0" step="0.01" value="<?= htmlspecialchars($moto['prix']) ?>">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Mettre à jour
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