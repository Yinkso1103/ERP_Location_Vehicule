<?php
// ======================================================
// ROUTEUR PRINCIPAL 
// ======================================================

// Définir le chemin absolu vers le dossier app
define('APP_PATH', dirname(__DIR__) . '/app/');

// Charger automatiquement les classes (controllers, models,config)
spl_autoload_register(function ($class) {
    $folders = ['controllers', 'models', 'config'];
    foreach ($folders as $folder) {
        $path = APP_PATH . $folder . '/' . $class . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Récupérer les paramètres depuis l’URL
// index.php?controller=user&action=create
$controllerName = $_GET['controller'] ?? 'user'; // valeur par défaut
$actionName     = $_GET['action'] ?? 'index';    // valeur par défaut

// Sécuriser les noms reçus
$controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
$actionName     = preg_replace('/[^a-zA-Z0-9_]/', '', $actionName);

// Construire le nom du contrôleur et son chemin
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerPath  = APP_PATH . 'controllers/' . $controllerClass . '.php';

// Vérifier que le fichier existe
if (!file_exists($controllerPath)) {
    http_response_code(404);
    die("<h2>Erreur 404</h2><p>Contrôleur introuvable : <strong>$controllerClass</strong></p>");
}

// Charger le contrôleur
require_once $controllerPath;

// Vérifier que la classe existe
if (!class_exists($controllerClass)) {
    http_response_code(500);
    die("<h2>Erreur interne</h2><p>Classe <strong>$controllerClass</strong> non trouvée.</p>");
}

//Instancier le contrôleur
$controller = new $controllerClass();

// 1Vérifier que la méthode demandée existe
if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    die("<h2>Erreur 404</h2><p>Action introuvable : <strong>$actionName</strong></p>");
}

//  Exécuter l’action
$controller->$actionName();