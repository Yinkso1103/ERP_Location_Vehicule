<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un devis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Modifier le devis <strong><?= htmlspecialchars($devis['numero_devis']) ?></strong>
                    </h3>
                </div>
                <div class="card-body">

                    <!-- Infos non modifiables -->
                    <div class="alert alert-light border mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="fas fa-user"></i> Prospect :</strong>
                                    <?= htmlspecialchars($devis['prospect_nom']) ?>
                                    <?= htmlspecialchars($devis['prospect_prenom']) ?>
                                </p>
                                <p class="mb-1"><strong><i class="fas fa-motorcycle"></i> Véhicule :</strong>
                                    <span class="badge bg-info"><?= strtoupper(htmlspecialchars($devis['type_vehicule'])) ?></span>
                                    <?= htmlspecialchars($devis['marque_vehicule']) ?>
                                    <?= htmlspecialchars($devis['modele_vehicule']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="fas fa-tag"></i> Prix catalogue :</strong>
                                    <?= number_format($devis['prix_vehicule'], 2, ',', ' ') ?> €
                                </p>
                                <p class="mb-1"><strong><i class="fas fa-calendar"></i> Créé le :</strong>
                                    <?= htmlspecialchars($devis['date_creation']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification -->
                    <form method="POST" action="index.php?controller=devis&action=update&id=<?= (int)$devis['id_devis'] ?>">

                        <!-- Remise -->
                        <div class="mb-3">
                            <label for="remise" class="form-label">
                                <i class="fas fa-percent"></i> Remise (%)
                            </label>
                            <input type="number"
                                   class="form-control"
                                   id="remise"
                                   name="remise"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   value="<?= htmlspecialchars($devis['remise']) ?>"
                                   oninput="calculerPrixFinal(this.value)">
                        </div>

                        <!-- Prix final calculé en temps réel -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-calculator"></i> Prix final estimé
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-euro-sign"></i></span>
                                <input type="text"
                                       class="form-control fw-bold"
                                       id="prix_final_affiche"
                                       readonly
                                       value="<?= number_format($devis['prix_final'], 2, ',', ' ') ?> €">
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="statut" class="form-label">
                                <i class="fas fa-flag"></i> Statut
                            </label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="en_attente" <?= $devis['statut'] === 'en_attente' ? 'selected' : '' ?>>
                                    ⏳ En attente
                                </option>
                                <option value="valide" <?= $devis['statut'] === 'valide' ? 'selected' : '' ?>>
                                    ✅ Validé
                                </option>
                                <option value="refuse" <?= $devis['statut'] === 'refuse' ? 'selected' : '' ?>>
                                    ❌ Refusé
                                </option>
                            </select>
                        </div>

                        <!-- Commentaire -->
                        <div class="mb-4">
                            <label for="commentaire" class="form-label">
                                <i class="fas fa-comment"></i> Commentaire
                            </label>
                            <textarea class="form-control"
                                      id="commentaire"
                                      name="commentaire"
                                      rows="3"
                                      placeholder="Commentaire optionnel..."><?= htmlspecialchars($devis['commentaire'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?controller=devis&action=index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-warning text-dark fw-bold">
                                <i class="fas fa-save"></i> Enregistrer les modifications
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

<script>
    const prixVehicule = <?= (float)$devis['prix_vehicule'] ?>;

    function calculerPrixFinal(remise) {
        const r = parseFloat(remise) || 0;
        const final = prixVehicule - (prixVehicule * r / 100);
        document.getElementById('prix_final_affiche').value =
            final.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    }
</script>

</body>
</html>