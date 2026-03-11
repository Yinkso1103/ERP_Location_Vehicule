<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospects archivés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-archive"></i> Prospects archivés</h2>
        <a href="index.php?controller=prospect&action=index" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux prospects actifs
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?php if (isset($prospects) && is_array($prospects) && count($prospects) > 0): ?>
                <table class="table table-hover table-striped">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Ville</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prospects as $p): ?>
                        <tr class="table-secondary">
                            <td><?= htmlspecialchars($p['id_prospect']) ?></td>
                            <td><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= htmlspecialchars($p['prenom']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['telephone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($p['ville'] ?? '-') ?></td>
                            <td>
                                <a href="index.php?controller=prospect&action=restore&id=<?= $p['id_prospect'] ?>" 
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Restaurer ce prospect ?')" title="Restaurer">
                                    <i class="fas fa-undo"></i> Restaurer
                                </a>
                                <a href="index.php?controller=prospect&action=delete&id=<?= $p['id_prospect'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('⚠️ Supprimer définitivement ce prospect ? Action irréversible !')" 
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
                    <i class="fas fa-info-circle"></i> Aucun prospect archivé.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="alert alert-warning mt-3">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Note :</strong> Les prospects archivés peuvent être restaurés ou supprimés définitivement.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>