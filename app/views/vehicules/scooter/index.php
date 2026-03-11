<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Scooters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bicycle text-info"></i> Liste des Scooters</h2>
        <div class="d-flex gap-2">
            <a href="index.php?controller=vehicule&action=createScooter" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouveau scooter
            </a>
            <a href="index.php?controller=vehicule&action=archivedScooter" class="btn btn-outline-secondary">
                <i class="fas fa-archive"></i> Archives
            </a>
            <a href="index.php?controller=vehicule&action=importScooter" class="btn btn-outline-primary">
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
                        <th>Cylindrée</th>
                        <th>Année</th>
                        <th>Couleur</th>
                        <th>Coffre</th>
                        <th>Prix</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($scooters)): ?>
                        <?php foreach ($scooters as $scooter): ?>
                        <tr>
                            <td class="text-muted"><?= htmlspecialchars($scooter['id_scooter']) ?></td>
                            <td><strong><?= htmlspecialchars($scooter['marque']) ?></strong></td>
                            <td><?= htmlspecialchars($scooter['modele']) ?></td>
                            <td><?= htmlspecialchars($scooter['cylindree']) ?> cc</td>
                            <td><?= htmlspecialchars($scooter['annee']) ?></td>
                            <td><?= htmlspecialchars($scooter['couleur']) ?></td>
                            <td>
                                <?php if ($scooter['coffre']): ?>
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Oui</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= number_format($scooter['prix'], 2, ',', ' ') ?> €</strong></td>
                            <td class="text-center">
                                <a href="index.php?controller=vehicule&action=editScooter&id=<?= $scooter['id_scooter'] ?>"
                                   class="btn btn-sm btn-primary me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=vehicule&action=archiveScooter&id=<?= $scooter['id_scooter'] ?>"
                                   class="btn btn-sm btn-warning"
                                   onclick="return confirm('Archiver ce scooter ?')" title="Archiver">
                                    <i class="fas fa-archive"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-bicycle fa-2x mb-2 d-block"></i>
                                Aucun scooter enregistré.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lien vers motos -->
    <div class="mt-3">
        <a href="index.php?controller=vehicule&action=indexMoto" class="btn btn-outline-danger">
            <i class="fas fa-motorcycle"></i> Voir les Motos
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>