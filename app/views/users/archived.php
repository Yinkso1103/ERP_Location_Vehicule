<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs archivés</title>
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
            <h2><i class="fas fa-archive"></i> Utilisateurs archivés</h2>
            <a href="index.php?controller=user&action=index" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Retour aux utilisateurs actifs
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
                    <table class="table table-hover table-striped">
                        <thead class="table-secondary">
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
                            <tr class="table-secondary">
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
                                    <a href="index.php?controller=user&action=restore&id=<?= $u['id_utilisateur'] ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Restaurer cet utilisateur ?')" title="Restaurer">
                                        <i class="fas fa-undo"></i> Restaurer
                                    </a>
                                    <a href="index.php?controller=user&action=delete&id=<?= $u['id_utilisateur'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('⚠️ ATTENTION : Supprimer définitivement cet utilisateur ? Cette action est irréversible !')" 
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
                        <i class="fas fa-info-circle"></i> Aucun utilisateur archivé.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="alert alert-warning mt-3">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Note :</strong> Les utilisateurs archivés ne peuvent plus se connecter mais leurs données sont conservées. 
            Vous pouvez les restaurer à tout moment ou les supprimer définitivement.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
</div>
</body>
</html>