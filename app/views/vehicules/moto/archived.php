<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motos Archivées</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../../partials/navbar.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-archive text-secondary"></i> Motos Archivées</h2>
        <a href="index.php?controller=vehicule&action=indexMoto" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Type</th>
                        <th>Cylindrée</th>
                        <th>Année</th>
                        <th>Prix</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($motos)): ?>
                        <?php foreach ($motos as $moto): ?>
                        <tr class="text-muted">
                            <td><?= htmlspecialchars($moto['id_moto']) ?></td>
                            <td><?= htmlspecialchars($moto['marque']) ?></td>
                            <td><?= htmlspecialchars($moto['modele']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($moto['type_moto']) ?></span></td>
                            <td><?= htmlspecialchars($moto['cylindree']) ?> cc</td>
                            <td><?= htmlspecialchars($moto['annee']) ?></td>
                            <td><?= number_format($moto['prix'], 2, ',', ' ') ?> €</td>
                            <td class="text-center">
                                <a href="index.php?controller=vehicule&action=restoreMoto&id=<?= $moto['id_moto'] ?>"
                                   class="btn btn-sm btn-success me-1" title="Restaurer"
                                   onclick="return confirm('Restaurer cette moto ?')">
                                   <i class="fas fa-undo"></i>                                </a>
                                <a href="index.php?controller=vehicule&action=deleteMoto&id=<?= $moto['id_moto'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer définitivement cette moto ?')" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-archive fa-2x mb-2 d-block"></i>
                                Aucune moto archivée.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>