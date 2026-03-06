<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
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
            <h2><i class="fas fa-user-check"></i> Liste des Clients</h2>
            <div>
                <a href="index.php?controller=client&action=archived" class="btn btn-secondary me-2">
                    <i class="fas fa-archive"></i> Voir les archives
                </a>
                <a href="index.php?controller=client&action=export" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exporter CSV
                </a>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Info :</strong> Les clients sont d'anciens prospects dont le devis a été validé.
        </div>

        <div class="card shadow">
            <div class="card-body">
                <?php if (isset($clients) && is_array($clients) && count($clients) > 0): ?>
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Client</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Ville</th>
                                <th>Date conversion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($clients as $c): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($c['numero_client']) ?></strong></td>
                                <td><?= htmlspecialchars($c['nom']) ?></td>
                                <td><?= htmlspecialchars($c['prenom']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['telephone']) ?></td>
                                <td><?= htmlspecialchars($c['ville']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['date_conversion'])) ?></td>
                                <td>
                                    <a href="index.php?controller=client&action=view&id=<?= $c['id_client'] ?>" 
                                       class="btn btn-sm btn-info text-white" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?controller=client&action=archive&id=<?= $c['id_client'] ?>" 
                                       class="btn btn-sm btn-warning"
                                       onclick="return confirm('Archiver ce client ?')" 
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
                        <i class="fas fa-info-circle"></i> Aucun client trouvé.
                        <br><br>
                        <small>Les clients sont créés automatiquement lorsque vous validez un devis.</small>
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