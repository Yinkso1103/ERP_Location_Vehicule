<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Devis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-invoice"></i> Liste des Devis</h2>
            <div>
                <a href="index.php?controller=devis&action=archived" class="btn btn-secondary me-2">
                    <i class="fas fa-archive"></i> Voir les archives
                </a>
                <a href="index.php?controller=devis&action=create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un devis
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <?php if (isset($devis) && is_array($devis) && count($devis) > 0): ?>
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Devis</th>
                                <th>Prospect</th>
                                <th>Véhicule</th>
                                <th>Prix</th>
                                <th>Remise</th>
                                <th>Prix Final</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($devis as $d): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($d['numero_devis']) ?></strong></td>
                                <td><?= htmlspecialchars($d['prospect_nom']) ?> <?= htmlspecialchars($d['prospect_prenom']) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= strtoupper($d['type_vehicule']) ?></span><br>
                                    <?= htmlspecialchars($d['marque_vehicule']) ?> <?= htmlspecialchars($d['modele_vehicule']) ?>
                                </td>
                                <td><?= number_format($d['prix_vehicule'], 2, ',', ' ') ?> €</td>
                                <td><?= $d['remise'] ?>%</td>
                                <td><strong><?= number_format($d['prix_final'], 2, ',', ' ') ?> €</strong></td>
                                <td>
                                    <?php if($d['statut'] == 'en_attente'): ?>
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    <?php elseif($d['statut'] == 'valide'): ?>
                                        <span class="badge bg-success">Validé</span>
                                    <?php elseif($d['statut'] == 'refuse'): ?>
                                        <span class="badge bg-danger">Refusé</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($d['statut']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($d['date_creation'])) ?></td>
                                <td>
                                    <a href="index.php?controller=devis&action=view&id=<?= $d['id_devis'] ?>" 
                                       class="btn btn-sm btn-info text-white" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if($d['statut'] == 'en_attente'): ?>
                                        <a href="index.php?controller=devis&action=valider&id=<?= $d['id_devis'] ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('✅ Valider ce devis et convertir le prospect en client ?')" 
                                           title="Valider le devis">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="index.php?controller=devis&action=refuser&id=<?= $d['id_devis'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Refuser ce devis ?')" 
                                           title="Refuser">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <a href="index.php?controller=devis&action=edit&id=<?= $d['id_devis'] ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="index.php?controller=devis&action=archive&id=<?= $d['id_devis'] ?>" 
                                       class="btn btn-sm btn-warning"
                                       onclick="return confirm('Archiver ce devis ?')" 
                                       title="Archiver">
                                        <i class="fas fa-archive"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucun devis trouvé.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <i class="fas fa-lightbulb"></i> 
            <strong>Info :</strong> Lorsqu'un devis est validé, le prospect est automatiquement converti en client !
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
    </div>
</body>
</html>