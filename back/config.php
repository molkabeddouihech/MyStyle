<?php
class config
{   private static $pdo = null;
    private static $firstConnection = true; // Nouveau flag pour détecter la première connexion
    
    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername="localhost";
            $username="root";
            $password ="";
            $dbname="site_angular";
            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname",
                        $username,
                        $password
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Message de confirmation UNIQUEMENT à la première connexion
                if(self::$firstConnection) {
                    /*echo "Connexion à la base de données établie avec succès!";*/
                    self::$firstConnection = false;
                }
                
            } catch (Exception $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// Test de la connexion
$conn = config::getConnexion();
?>