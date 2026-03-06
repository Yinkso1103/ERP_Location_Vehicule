<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">

    <!-- ÉTAPE 1 : Navigation simple -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-chart-line"></i> GestionRH
            </span>
            <div class="d-flex">
                <span class="text-white me-3">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION["utilisateur"]["prenom"]) ?>
                </span>
                <a href="index.php?controller=dashboard&action=logout" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- ÉTAPE 2 : Message de bienvenue amélioré -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-hand-wave"></i> 
                        Bienvenue sur votre tableau de bord, <?= htmlspecialchars($_SESSION["utilisateur"]["nom"]) ?> !
                    </h4>
                    <p class="mb-0">Vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION["utilisateur"]["nom_role"]) ?></strong></p>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 3 : Cartes de statistiques simples -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Gérer les utilisateurs</h5>
                        <p class="card-text">Consulter, créer et modifier les utilisateurs</p>
                        <a href="index.php?controller=user&action=index" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Accéder
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-file-csv fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Importer des données</h5>
                        <p class="card-text">Importer des utilisateurs via fichier CSV</p>
                        <a href="index.php?controller=user&action=import" class="btn btn-info text-white">
                            <i class="fas fa-upload"></i> Importer
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-archive fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Archives</h5>
                        <p class="card-text">Consulter les utilisateurs archivés</p>
                        <a href="index.php?controller=user&action=archived" class="btn btn-warning">
                            <i class="fas fa-box"></i> Archives
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 4 : Informations utilisateur -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de votre compte</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom complet :</strong> 
                                    <?= htmlspecialchars($_SESSION["utilisateur"]["nom"]) ?> 
                                    <?= htmlspecialchars($_SESSION["utilisateur"]["prenom"]) ?>
                                </p>
                                <p><strong>Email :</strong> 
                                    <?= htmlspecialchars($_SESSION["utilisateur"]["email"]) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Rôle :</strong> 
                                    <span class="badge bg-<?= $_SESSION["utilisateur"]["id_role"] == 1 ? 'danger' : ($_SESSION["utilisateur"]["id_role"] == 2 ? 'warning' : 'info') ?>">
                                        <?= htmlspecialchars($_SESSION["utilisateur"]["nom_role"]) ?>
                                    </span>
                                </p>
                                <p><strong>Date de connexion :</strong> <?= date('d/m/Y H:i') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 5 : Actions rapides -->
        <div class="row mt-4 mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="index.php?controller=user&action=create" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Créer un utilisateur
                            </a>
                            <a href="index.php?controller=user&action=export" class="btn btn-primary">
                                <i class="fas fa-download"></i> Exporter les données
                            </a>
                            <a href="index.php?controller=user&action=import" class="btn btn-info text-white">
                                <i class="fas fa-file-import"></i> Importer CSV
                            </a>
                        </div>
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