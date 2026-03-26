<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Définir le chemin absolu vers le dossier app
define('APP_PATH', __DIR__ . '/app/');

// Charge automatique des classes
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

// Récupérer les paramètres depuis l'URL
$controllerName = $_GET['controller'] ?? 'auth';
$actionName     = $_GET['action'] ?? 'index';

// Sécuriser les noms reçus
$controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
$actionName     = preg_replace('/[^a-zA-Z0-9_]/', '', $actionName);

// Pages accessibles sans être connecté
$pagesPubliques = [
    'auth' => ['index', 'login', 'logout']
];

// Vérifier si la page est publique
$estPublique = isset($pagesPubliques[$controllerName]) 
    && in_array($actionName, $pagesPubliques[$controllerName]);

// Si pas connecté et page privée → rediriger vers login
if (!$estPublique && !isset($_SESSION['utilisateur'])) {
    header('Location: index.php?controller=auth&action=index');
    exit;
}

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

// Instancier le contrôleur
$controller = new $controllerClass();

// Vérifier que la méthode demandée existe
if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    die("<h2>Erreur 404</h2><p>Action introuvable : <strong>$actionName</strong></p>");
}

// Exécuter l'action
$controller->$actionName();