<?php
require_once __DIR__ . '/../models/UserDao.php';
class AuthController{

    public function index(){
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function indexDashboard(){
        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function login(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            if(empty($_POST)['email']) || empty($_POST['password']){
                $message = "Veuillez remplir tous les champs";
                require_once __DIR__ . '/../views/aut/login.php';
                echo "<p style='color:red;'>:$message</p>";
                return;
            }

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $utilisateur = UserDao::recupUtilisateurByEmail()

            if($utilisateur && password_verify($password,$utilisateur["password"])){
            session_start();
            $_SESSION["utilisateur"] = $utilisateur;
            header("Location: index.php?controller=user&action=index");
            }

        }
    }

}

?>