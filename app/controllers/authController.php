<?php
session_start();

require_once __DIR__ . '/../models/UserDao.php';

class AuthController {

    // Affiche la page de connexion
    public function index() {
        // Si déjà connecté, rediriger vers le dashboard
        if (isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=user&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Traite le formulaire de connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Vérifier que les champs sont remplis
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $error = "Veuillez remplir tous les champs.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            $email    = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Récupérer l'utilisateur par email (méthode correcte)
            $utilisateur = UserDao::getUserByEmail($email);

            // Vérifier le mot de passe (champ "mot_de_passe" en BDD)
            if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {

                // Vérifier que le compte n'est pas archivé
                if ($utilisateur['archive'] == 1) {
                    $error = "Ce compte est désactivé. Contactez l'administrateur.";
                    require_once __DIR__ . '/../views/auth/login.php';
                    return;
                }

                $_SESSION['utilisateur'] = $utilisateur;
                header('Location: index.php?controller=user&action=index');
                exit;

            } else {
                $error = "Email ou mot de passe incorrect.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }
        }

        // Accès direct en GET → afficher le formulaire
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Déconnexion
    public function logout() {
        session_destroy();
        header('Location: index.php?controller=auth&action=index');
        exit;
    }
}
?>