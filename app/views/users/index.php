<?php

// controle de l'existance de la session.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> Liste des utilisateurs actifs</h2>
            <div>
                <a href="index.php?controller=user&action=archived" class="btn btn-secondary me-2">
                    <i class="fas fa-archive"></i> Voir les archives
                </a>
                <a href="index.php?controller=user&action=import" class="btn btn-info text-white me-2">
                    <i class="fas fa-file-csv"></i> Importer CSV
                </a>
                <a href="index.php?controller=user&action=create" class="btn btn-success">
                    <i class="fas fa-plus"></i> Créer un utilisateur
                </a>
            </div>
        </div>
        <div class="card shadow mb-3">
                    <div class="card-body bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-download"></i> Exporter les données</h5>
                            <div>
                                <a href="index.php?controller=user&action=export" 
                                class="btn btn-sm btn-success" 
                                title="Exporter tous les utilisateurs actifs au format CSV">
                                    <i class="fas fa-file-excel"></i> Exporter les utilisateurs actifs (CSV)
                                </a>
                            </div>
                        </div>
                    </div>
        <div class="card shadow">
            <div class="card-body">
                <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['id_utilisateur']) ?></td>
                                <td><?= htmlspecialchars($u['nom']) ?></td>
                                <td><?= htmlspecialchars($u['prenom']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <?php if($u['id_role'] == 1): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php elseif($u['id_role'] == 2): ?>
                                        <span class="badge bg-warning text-dark">RH</span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-dark">Employé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?controller=user&action=edit&id=<?= $u['id_utilisateur'] ?>" 
                                       class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?controller=user&action=archive&id=<?= $u['id_utilisateur'] ?>" 
                                       class="btn btn-sm btn-warning"
                                       onclick="return confirm('Archiver cet utilisateur ?')" title="Archiver">
                                        <i class="fas fa-archive"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucun utilisateur actif trouvé.
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