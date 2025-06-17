<?php
class Config {
    private static $conn = null;

    public static function init() {
        if (self::$conn === null) {
            $host = 'localhost';
            $db = 'site_angular';
            $user = 'root';
            $pass = '';

            try {
                self::$conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
    }

    public static function getConnexion() {
        if (self::$conn === null) {
            self::init();
        }
        return self::$conn;
    }
}

// Initialiser la connexion au chargement du fichier
Config::init();
