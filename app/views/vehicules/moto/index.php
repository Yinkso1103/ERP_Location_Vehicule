<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Motos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-motorcycle text-danger"></i> Liste des Motos</h2>
        <div class="d-flex gap-2">
            <a href="index.php?controller=vehicule&action=createMoto" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouvelle moto
            </a>
            <a href="index.php?controller=vehicule&action=archivedMoto" class="btn btn-outline-secondary">
                <i class="fas fa-archive"></i> Archives
            </a>
            <a href="index.php?controller=vehicule&action=importMoto" class="btn btn-outline-primary">
                <i class="fas fa-file-csv"></i> Import CSV
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Type</th>
                        <th>Cylindrée</th>
                        <th>Année</th>
                        <th>Couleur</th>
                        <th>Prix</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($motos)): ?>
                        <?php foreach ($motos as $moto): ?>
                        <tr>
                            <td class="text-muted"><?= htmlspecialchars($moto['id_moto']) ?></td>
                            <td><strong><?= htmlspecialchars($moto['marque']) ?></strong></td>
                            <td><?= htmlspecialchars($moto['modele']) ?></td>
                            <td><span class="badge bg-danger"><?= htmlspecialchars($moto['type_moto']) ?></span></td>
                            <td><?= htmlspecialchars($moto['cylindree']) ?> cc</td>
                            <td><?= htmlspecialchars($moto['annee']) ?></td>
                            <td><?= htmlspecialchars($moto['couleur']) ?></td>
                            <td><strong><?= number_format($moto['prix'], 2, ',', ' ') ?> €</strong></td>
                            <td class="text-center">
                                <a href="index.php?controller=vehicule&action=editMoto&id=<?= $moto['id_moto'] ?>"
                                   class="btn btn-sm btn-primary me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=vehicule&action=archiveMoto&id=<?= $moto['id_moto'] ?>"
                                   class="btn btn-sm btn-warning"
                                   onclick="return confirm('Archiver cette moto ?')" title="Archiver">
                                    <i class="fas fa-archive"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-motorcycle fa-2x mb-2 d-block"></i>
                                Aucune moto enregistrée.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lien vers scooters -->
    <div class="mt-3">
        <a href="index.php?controller=vehicule&action=indexScooter" class="btn btn-outline-info">
            <i class="fas fa-bicycle"></i> Voir les Scooters
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>