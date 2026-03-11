<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importer des utilisateurs (CSV)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../partials/navbar.php';?>

<div class="container mt-4">
 
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0"><i class="fas fa-file-csv"></i> Importer des utilisateurs via CSV</h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Messages de succès/erreur -->
                        <?php if (isset($success) && $success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <strong>Succès !</strong> 
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($errors) && count($errors) > 0): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Erreurs détectées :</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire d'upload -->
                        <form action="index.php?controller=user&action=importProcess" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="csv_file" class="form-label">
                                    <i class="fas fa-upload"></i> Sélectionnez votre fichier CSV
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="csv_file" 
                                       name="csv_file" 
                                       accept=".csv" 
                                       required>
                                <small class="form-text text-muted">
                                    Format accepté : .csv (maximum 2 Mo)
                                </small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?controller=user&action=index" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-file-import"></i> Importer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Format du fichier CSV</h5>
                    </div>
                    <div class="card-body">
                        <p>Votre fichier CSV doit respecter le format suivant :</p>
                        
                        <div class="alert alert-light border">
                            <strong>En-têtes obligatoires (première ligne) :</strong>
                            <pre class="mb-0 mt-2">nom,prenom,email,role_id</pre>
                        </div>
                        <a href="index.php?controller=user&action=downloadTemplate" class="btn btn-success">
                            <i class="fas fa-download"></i> Télécharger un modèle CSV
                        </a>
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