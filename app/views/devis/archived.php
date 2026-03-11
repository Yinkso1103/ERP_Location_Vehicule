<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis archivés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-archive"></i> Devis archivés</h2>
        <a href="index.php?controller=devis&action=index" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux devis actifs
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?php if (isset($devis) && is_array($devis) && count($devis) > 0): ?>
                <table class="table table-hover table-striped">
                    <thead class="table-secondary">
                        <tr>
                            <th>Numéro</th>
                            <th>Prospect</th>
                            <th>Véhicule</th>
                            <th>Prix final</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($devis as $d): ?>
                        <tr class="table-secondary">
                            <td><strong><?= htmlspecialchars($d['numero_devis']) ?></strong></td>
                            <td><?= htmlspecialchars($d['prospect_nom']) ?> <?= htmlspecialchars($d['prospect_prenom']) ?></td>
                            <td>
                                <span class="badge bg-info"><?= strtoupper($d['type_vehicule']) ?></span>
                                <?= htmlspecialchars($d['marque_vehicule']) ?> <?= htmlspecialchars($d['modele_vehicule']) ?>
                            </td>
                            <td><strong><?= number_format($d['prix_final'], 2, ',', ' ') ?> €</strong></td>
                            <td>
                                <?php if($d['statut'] == 'en_attente'): ?>
                                    <span class="badge bg-warning text-dark">⏳ En attente</span>
                                <?php elseif($d['statut'] == 'valide'): ?>
                                    <span class="badge bg-success">✅ Validé</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">❌ Refusé</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($d['date_creation']) ?></td>
                            <td>
                                <a href="index.php?controller=devis&action=restore&id=<?= $d['id_devis'] ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Restaurer ce devis ?')" title="Restaurer">
                                    <i class="fas fa-undo"></i> Restaurer
                                </a>
                                <a href="index.php?controller=devis&action=delete&id=<?= $d['id_devis'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer définitivement ce devis ? Action irréversible !')"
                                   title="Supprimer définitivement">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucun devis archivé.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="alert alert-warning mt-3">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Note :</strong> Les devis archivés sont conservés à titre d'historique. Vous pouvez les restaurer ou les supprimer définitivement.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>