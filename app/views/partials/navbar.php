<?php
/**
 * navbar.php — Barre de navigation globale
 * À inclure en haut de chaque vue : <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
 * 
 * Dépendances (déjà chargées dans la plupart des vues) :
 *   - Bootstrap 5.3
 *   - Font Awesome 6.5
 */

// Page courante pour surligner le lien actif
$currentController = $_GET['controller'] ?? 'user';
$currentAction     = $_GET['action']     ?? 'index';

// Helper : retourne 'active' si le contrôleur correspond
function navActive(string $ctrl, string $current): string {
    return $ctrl === $current ? 'active' : '';
}
?>

<!-- ════════════════════════════════════════════════════════
     NAVBAR GLOBALE — Scooter ERP
     ════════════════════════════════════════════════════════ -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
  /* ── Variables ─────────────────────────── */
  :root {
    --nav-bg:      #0d1b2a;
    --nav-accent:  #e94f1c;
    --nav-text:    #c8d6e5;
    --nav-hover:   #ffffff;
    --nav-active-bg: rgba(233,79,28,.15);
    --nav-height:  62px;
  }

  /* ── Barre principale ───────────────────── */
  #scooter-navbar {
    background: var(--nav-bg);
    height: var(--nav-height);
    padding: 0 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.45);
    position: sticky;
    top: 0;
    z-index: 1030;
  }

  /* ── Logo ───────────────────────────────── */
  #scooter-navbar .navbar-brand {
    color: #fff !important;
    font-weight: 700;
    font-size: 1.15rem;
    letter-spacing: .5px;
    display: flex;
    align-items: center;
    gap: .55rem;
  }
  #scooter-navbar .navbar-brand .brand-icon {
    color: var(--nav-accent);
    font-size: 1.35rem;
  }
  #scooter-navbar .navbar-brand span {
    color: var(--nav-accent);
  }

  /* ── Liens nav ──────────────────────────── */
  #scooter-navbar .nav-link {
    color: var(--nav-text) !important;
    font-size: .82rem;
    font-weight: 500;
    letter-spacing: .3px;
    padding: .35rem .7rem !important;
    border-radius: 6px;
    transition: color .18s, background .18s;
    display: flex;
    align-items: center;
    gap: .4rem;
  }
  #scooter-navbar .nav-link:hover {
    color: var(--nav-hover) !important;
    background: rgba(255,255,255,.07);
  }
  #scooter-navbar .nav-link.active {
    color: var(--nav-hover) !important;
    background: var(--nav-active-bg);
    border-left: 3px solid var(--nav-accent);
    padding-left: calc(.7rem - 3px) !important;
  }
  #scooter-navbar .nav-link i {
    font-size: .9rem;
    width: 16px;
    text-align: center;
  }

  /* ── Séparateur vertical ────────────────── */
  #scooter-navbar .nav-divider {
    width: 1px;
    background: rgba(255,255,255,.12);
    height: 24px;
    align-self: center;
    margin: 0 .25rem;
  }

  /* ── Badge utilisateur connecté ─────────── */
  #scooter-navbar .user-badge {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 20px;
    padding: .25rem .75rem;
    color: var(--nav-text);
    font-size: .78rem;
    display: flex;
    align-items: center;
    gap: .4rem;
  }
  #scooter-navbar .user-badge .role-tag {
    background: var(--nav-accent);
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: .5px;
  }

  /* ── Bouton déconnexion ─────────────────── */
  #scooter-navbar .btn-logout {
    background: transparent;
    border: 1px solid rgba(233,79,28,.5);
    color: var(--nav-accent) !important;
    font-size: .78rem;
    padding: .25rem .65rem;
    border-radius: 6px;
    transition: background .18s, color .18s;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: .35rem;
  }
  #scooter-navbar .btn-logout:hover {
    background: var(--nav-accent);
    color: #fff !important;
  }

  /* ── Toggler mobile ─────────────────────── */
  #scooter-navbar .navbar-toggler {
    border-color: rgba(255,255,255,.2);
  }
  #scooter-navbar .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28200,214,229,.8%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }

  /* ── Espacement du body sous la navbar ───── */
  body { padding-top: 0; }
</style>

<nav id="scooter-navbar" class="navbar navbar-expand-lg">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand" href="index.php?controller=user&action=index">
      <i class="fas fa-motorcycle brand-icon"></i>
      Scooter<span>ERP</span>
    </a>

    <!-- Toggler mobile -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMain"
            aria-controls="navMain" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens principaux -->
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto align-items-lg-center gap-1">

        <!-- Utilisateurs (admin uniquement) -->
        <?php if (isset($_SESSION['utilisateur']) && ($_SESSION['utilisateur']['id_role'] ?? 0) == 1): ?>
        <li class="nav-item">
          <a class="nav-link <?= navActive('user', $currentController) ?>"
             href="index.php?controller=user&action=index">
            <i class="fas fa-users"></i> Utilisateurs
          </a>
        </li>
        <li class="nav-divider d-none d-lg-flex"></li>
        <?php endif; ?>

        <!-- Prospects -->
        <li class="nav-item">
          <a class="nav-link <?= navActive('prospect', $currentController) ?>"
             href="index.php?controller=prospect&action=index">
            <i class="fas fa-user-plus"></i> Prospects
          </a>
        </li>

        <!-- Clients -->
        <li class="nav-item">
          <a class="nav-link <?= navActive('client', $currentController) ?>"
             href="index.php?controller=client&action=index">
            <i class="fas fa-user-check"></i> Clients
          </a>
        </li>

        <li class="nav-divider d-none d-lg-flex"></li>

        <!-- Véhicules -->
        <li class="nav-item">
          <a class="nav-link <?= navActive('vehicule', $currentController) ?>"
             href="index.php?controller=vehicule&action=index">
            <i class="fas fa-motorcycle"></i> Véhicules
          </a>
        </li>

        <li class="nav-divider d-none d-lg-flex"></li>

        <!-- Devis -->
        <li class="nav-item">
          <a class="nav-link <?= navActive('devis', $currentController) ?>"
             href="index.php?controller=devis&action=index">
            <i class="fas fa-file-invoice"></i> Devis
          </a>
        </li>

      </ul>

      <!-- Zone utilisateur connecté -->
      <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">

        <?php if (isset($_SESSION['utilisateur'])): ?>
          <?php
            $u        = $_SESSION['utilisateur'];
            $prenom   = htmlspecialchars($u['prenom'] ?? '');
            $nom      = htmlspecialchars($u['nom']    ?? '');
            $roleId   = $u['id_role'] ?? 0;
            $roleLabels = [1 => 'Admin', 2 => 'Commercial', 3 => 'Comptable'];
            $roleLabel  = $roleLabels[$roleId] ?? 'Utilisateur';
          ?>
          <div class="user-badge">
            <i class="fas fa-user-circle"></i>
            <?= $prenom ?> <?= $nom ?>
            <span class="role-tag"><?= $roleLabel ?></span>
          </div>
          <a href="index.php?controller=auth&action=logout" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
          </a>
        <?php else: ?>
          <a href="index.php?controller=auth&action=index" class="btn-logout">
            <i class="fas fa-sign-in-alt"></i> Connexion
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>