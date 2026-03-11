<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-circle"></i> Fiche client</h2>
                <a href="index.php?controller=client&action=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <!-- Infos client -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card"></i>
                        <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>
                        <span class="badge bg-light text-primary ms-2"><?= htmlspecialchars($client['numero_client']) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-envelope text-muted me-2"></i><strong>Email :</strong>
                                <?= htmlspecialchars($client['email']) ?>
                            </p>
                            <p><i class="fas fa-phone text-muted me-2"></i><strong>Téléphone :</strong>
                                <?= htmlspecialchars($client['telephone'] ?? 'Non renseigné') ?>
                            </p>
                            <p><i class="fas fa-calendar text-muted me-2"></i><strong>Client depuis :</strong>
                                <?= htmlspecialchars($client['date_conversion']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-map-marker-alt text-muted me-2"></i><strong>Adresse :</strong>
                                <?= htmlspecialchars($client['adresse'] ?? 'Non renseignée') ?>
                            </p>
                            <p><i class="fas fa-city text-muted me-2"></i><strong>Ville :</strong>
                                <?= htmlspecialchars($client['code_postal'] ?? '') ?>
                                <?= htmlspecialchars($client['ville'] ?? 'Non renseignée') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des devis -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Historique des devis</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($devis) && is_array($devis) && count($devis) > 0): ?>
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Numéro</th>
                                    <th>Véhicule</th>
                                    <th>Prix final</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($devis as $d): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($d['numero_devis']) ?></strong></td>
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
                                        <a href="index.php?controller=devis&action=view&id=<?= $d['id_devis'] ?>"
                                           class="btn btn-sm btn-outline-primary" title="Voir le devis">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center mb-0">
                            <i class="fas fa-info-circle"></i> Aucun devis associé à ce client.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>