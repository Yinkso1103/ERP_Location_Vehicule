<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un prospect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Modifier un prospect</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?controller=prospect&action=update&id=<?= htmlspecialchars($prospect['id_prospect']) ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="<?= htmlspecialchars($prospect['nom']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                       value="<?= htmlspecialchars($prospect['prenom']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($prospect['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                   value="<?= htmlspecialchars($prospect['telephone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                   value="<?= htmlspecialchars($prospect['adresse'] ?? '') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville"
                                       value="<?= htmlspecialchars($prospect['ville'] ?? '') ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="code_postal" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="code_postal" name="code_postal"
                                       value="<?= htmlspecialchars($prospect['code_postal'] ?? '') ?>">
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?controller=prospect&action=index" class="btn btn-secondary">
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
</body>
</html>