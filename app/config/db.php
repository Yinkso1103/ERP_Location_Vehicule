<?php
class Db{
    private static $pdo = null;

public static function seConnecterBdd(){
        if(self::$pdo == null){
            $env = parse_ini_file(__DIR__ .'/../../.env');
            $host = $env['DB_HOST'];
            $dbname = $env['DB_NAME'];
            $username = $env['DB_USER'];
            $password = $env['DB_PASS'];
            $charset = $env['DB_CHARSET'];
            try{
            self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $username, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Erreurde connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$pdo;

}
}