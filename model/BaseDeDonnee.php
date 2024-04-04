<?php 
    /*  +-------------------------------------------------------------------------------+
        | Ce fichier contient l'ensemble de code pour se connecter a la base de donnée  |
        | Date de création : samedi 14 février 2024                                     |
        | Auteur : RAZANAKANAMBININTSOA Joseph                                          |
        +-------------------------------------------------------------------------------+
    */

    class BaseDeDonnee {
        private $host; 
        private $db_name; 
        private $username; 
        private $password; 

        public $connexion;

        public function __construct() {
            $this->host = "localhost"; 
            $this->db_name = "mini_chat"; 
            $this->username = "root"; 
            $this->password = "";   
            $this->connexion = null; 
        }

        public function recupererConnexion() {
            try {
                $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password); 
            }
            catch(Exception $e) {
                die('Erreur de connexion a la base de donnée : ' . $e->getMessage()); 
            }

            return $this->connexion; 
        }
    }
?>