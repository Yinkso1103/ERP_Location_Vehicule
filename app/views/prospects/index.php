<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Prospects</title>
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
            <h2><i class="fas fa-user-tie"></i> Liste des Prospects</h2>
            <div>
                <a href="index.php?controller=prospect&action=archived" class="btn btn-secondary me-2">
                    <i class="fas fa-archive"></i> Voir les archives
                </a>
                <a href="index.php?controller=prospect&action=export" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exporter CSV
                </a>
                <a href="index.php?controller=prospect&action=create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un prospect
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <?php if (isset($prospects) && is_array($prospects) && count($prospects) > 0): ?>
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Ville</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($prospects as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['id_prospect']) ?></td>
                                <td><?= htmlspecialchars($p['nom']) ?></td>
                                <td><?= htmlspecialchars($p['prenom']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
                                <td><?= htmlspecialchars($p['telephone']) ?></td>
                                <td><?= htmlspecialchars($p['ville']) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['date_creation'])) ?></td>
                                <td>
                                    <a href="index.php?controller=devis&action=create&prospect=<?= $p['id_prospect'] ?>" 
                                       class="btn btn-sm btn-success" 
                                       title="Créer un devis">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="index.php?controller=prospect&action=edit&id=<?= $p['id_prospect'] ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?controller=prospect&action=archive&id=<?= $p['id_prospect'] ?>" 
                                       class="btn btn-sm btn-warning"
                                       onclick="return confirm('Archiver ce prospect ?')" 
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
                        <i class="fas fa-info-circle"></i> Aucun prospect actif trouvé.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
</div>
</body>
</html>