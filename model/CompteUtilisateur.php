<?php 
    class CompteUtilisateur {
        private $connexion; 
        private $table = "compte_utilisateur"; 

        public $id_compte; 
        public $nom_utilisateur; 
        public $adresse_email; 
        public $mot_de_passe; 


        public function __construct($base_de_donnees) {
            $this->connexion = $base_de_donnees; 
        }


        public function creerUnCompte() {
            $sql = "INSERT INTO " . $this->table . "(nom_utilisateur, adresse_email, mot_de_passe) VALUES(:nom_utilisateur, :adresse_email, :mot_de_passe)";

            $requette = $this->connexion->prepare($sql); 

            $this->nom_utilisateur = strip_tags($this->nom_utilisateur); 
            $this->adresse_email = strip_tags($this->adresse_email); 
            $this->mot_de_passe = strip_tags($this->mot_de_passe); 

            $requette->bindParam(':nom_utilisateur', $this->nom_utilisateur); 
            $requette->bindParam(':adresse_email', $this->adresse_email); 
            $requette->bindParam(':mot_de_passe', $this->mot_de_passe); 

            if($requette->execute()) {
                return true; 
            }
            return false; 
        }


        public function modifierNomUtilisateur() {
            $sql = "UPDATE " . $this->table . " SET nom_utilisateur = :nom_utilisateur WHERE id_compte = :id_compte"; 
            
            $requette = $this->connexion->prepare($sql); 

            $this->nom_utilisateur = strip_tags($this->nom_utilisateur); 

            $requette->bindParam(':nom_utilisateur', $this->nom_utilisateur); 
            $requette->bindParam(':id_compte', $this->id_compte);

            if($requette->execute()) {
                return true; 
            }
            return false; 
        }


        public function modifierMotDePasse() {
            $sql = "UPDATE " . $this->table . " SET mot_de_passe = :mot_de_passe WHERE id_compte = :id_compte"; 
            
            $requette = $this->connexion->prepare($sql); 

            $this->mot_de_passe = strip_tags($this->mot_de_passe); 

            $requette->bindParam(':mot_de_passe', $this->mot_de_passe); 
            $requette->bindParam(':id_compte', $this->id_compte);

            if($requette->execute()) {
                return true; 
            }
            return false; 
        }

        public function authentification() {
            $sql = "SELECT * FROM " . $this->table . " WHERE adresse_email = :adresse_email"; 

            $requette = $this->connexion->prepare($sql); 

            $requette->bindParam(':adresse_email', $this->adresse_email); 

            $requette->execute();

            return $requette; 
        }
    }
?>