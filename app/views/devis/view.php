<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Devis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h2><i class="fas fa-file-invoice"></i> Détails du Devis</h2>
            <div>
                <button onclick="window.print()" class="btn btn-secondary me-2">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <a href="index.php?controller=devis&action=index" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <?php if (isset($devis) && $devis): ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Devis N° <?= htmlspecialchars($devis['numero_devis']) ?></h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <?php if($devis['statut'] == 'en_attente'): ?>
                            <span class="badge bg-warning fs-5">En attente</span>
                        <?php elseif($devis['statut'] == 'valide'): ?>
                            <span class="badge bg-success fs-5">Validé</span>
                        <?php elseif($devis['statut'] == 'refuse'): ?>
                            <span class="badge bg-danger fs-5">Refusé</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Informations du prospect -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2"><i class="fas fa-user"></i> Informations du Prospect</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> <?= htmlspecialchars($devis['prospect_nom']) ?> <?= htmlspecialchars($devis['prospect_prenom']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($devis['prospect_email']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($devis['prospect_telephone'] ?? 'Non renseigné') ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($devis['prospect_adresse'] ?? 'Non renseignée') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Informations du véhicule -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2"><i class="fas fa-motorcycle"></i> Véhicule</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Type :</strong> 
                                <span class="badge bg-info"><?= strtoupper(htmlspecialchars($devis['type_vehicule'])) ?></span>
                            </p>
                            <p><strong>Marque :</strong> <?= htmlspecialchars($devis['marque_vehicule']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Modèle :</strong> <?= htmlspecialchars($devis['modele_vehicule']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Prix et calculs -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2"><i class="fas fa-calculator"></i> Détails du Prix</h5>
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Prix du véhicule :</strong></td>
                            <td class="text-end"><?= number_format($devis['prix_vehicule'], 2, ',', ' ') ?> €</td>
                        </tr>
                        <tr>
                            <td><strong>Remise :</strong></td>
                            <td class="text-end"><?= $devis['remise'] ?>%</td>
                        </tr>
                        <tr>
                            <td><strong>Montant de la remise :</strong></td>
                            <td class="text-end">
                                - <?= number_format($devis['prix_vehicule'] * $devis['remise'] / 100, 2, ',', ' ') ?> €
                            </td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>PRIX FINAL :</strong></td>
                            <td class="text-end"><strong><?= number_format($devis['prix_final'], 2, ',', ' ') ?> €</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Commentaire -->
                <?php if (!empty($devis['commentaire'])): ?>
                <div class="mb-4">
                    <h5 class="border-bottom pb-2"><i class="fas fa-comment"></i> Commentaire</h5>
                    <p><?= nl2br(htmlspecialchars($devis['commentaire'])) ?></p>
                </div>
                <?php endif; ?>

                <!-- Dates -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2"><i class="fas fa-calendar"></i> Informations Complémentaires</h5>
                    <p><strong>Date de création :</strong> <?= date('d/m/Y H:i', strtotime($devis['date_creation'])) ?></p>
                    <?php if ($devis['statut'] == 'valide' && $devis['date_validation']): ?>
                        <p><strong>Date de validation :</strong> <?= date('d/m/Y H:i', strtotime($devis['date_validation'])) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Actions (masquées à l'impression) -->
                <div class="no-print">
                    <hr>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <?php if($devis['statut'] == 'en_attente'): ?>
                            <a href="index.php?controller=devis&action=valider&id=<?= $devis['id_devis'] ?>" 
                               class="btn btn-success"
                               onclick="return confirm('✅ Valider ce devis et convertir le prospect en client ?')">
                                <i class="fas fa-check"></i> Valider le devis
                            </a>
                            <a href="index.php?controller=devis&action=refuser&id=<?= $devis['id_devis'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Refuser ce devis ?')">
                                <i class="fas fa-times"></i> Refuser
                            </a>
                            <a href="index.php?controller=devis&action=edit&id=<?= $devis['id_devis'] ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Message de succès si le devis a été validé -->
        <?php if ($devis['statut'] == 'valide'): ?>
        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle"></i> 
            <strong>Devis validé !</strong> Le prospect a été converti en client avec succès.
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> Devis introuvable.
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
</div>
</body>
</html>