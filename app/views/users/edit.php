<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Modifier un utilisateur</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?controller=user&action=update&id=<?= htmlspecialchars($utilisateur['id_utilisateur']) ?>">
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                       value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role_id" class="form-label">Rôle</label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="1" <?= $utilisateur['id_role'] == 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?= $utilisateur['id_role'] == 2 ? 'selected' : '' ?>>RH</option>
                                    <option value="3" <?= $utilisateur['id_role'] == 3 ? 'selected' : '' ?>>Employé</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password_user" class="form-label">Nouveau mot de passe (facultatif)</label>
                                <input type="password" class="form-control" id="password_user" name="password_user" 
                                       placeholder="Laisser vide pour conserver l'ancien">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?controller=user&action=index" class="btn btn-secondary">
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
</div>
</body>
</html>