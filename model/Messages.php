<?php 
    class Messages{
        private $connexion; 
        private $table; 

        public $id_message; 
        public $date_envoi; 
        public $id_destinataire; 
        public $id_expediteur; 
        public $contenu; 


        public function __construct($base_de_donnees) {
            $this->connexion = $base_de_donnees; 
            $this->table = "messages"; 
        }


        public function envoyerMessage($email) {
            // On effectue l'insertion : 
            $sql = "INSERT INTO " . $this->table . "(id_destinataire, id_expediteur, contenu) VALUES(:id_destinataire, :id_expediteur, :contenu)";

            $requette = $this->connexion->prepare($sql); 
            $this->contenu = strip_tags($this->contenu); 

            $requette->bindParam(':id_destinataire', $this->id_destinataire); 
            $requette->bindParam(':id_expediteur', $this->id_expediteur); 
            $requette->bindParam(':contenu', $this->contenu); 

            if($requette->execute()) {
                return true; 
            } else {
                return false;
            }
        }


        public function supprimerUnMessage($id_compte, $id_expediteur, $id_message) {
            $sql = "";
        
            if($id_compte == $id_expediteur) {
                // Requette pour supprimer le message du coté expéditeur : 
                $sql = "UPDATE " . $this->table . " SET supprime_expediteur = TRUE WHERE id_message = :id_message";
            } else {
                // Requette pour supprimer le message du coté destinataire : 
                $sql = "UPDATE " . $this->table . " SET supprime_destinataire = TRUE WHERE id_message = :id_message";
            }

            $requette = $this->connexion->prepare($sql); 
            $requette->bindParam(':id_message', $id_message); 

            if($requette->execute()) {
                return true; 
            } 
            return false; 
        }

        public function listerMessageRecu($id_compte) {
            // $sql = "SELECT * FROM " . $this->table . ' WHERE id_destinataire = :id_destinataire AND supprime_destinataire = FALSE';
	    $sql = "SELECT compte_utilisateur.adresse_email AS id_expediteur, messages.contenu AS contenu, messages.date_envoi AS date_envoi, messages.id_message FROM messages, compte_utilisateur WHERE (messages.id_expediteur = compte_utilisateur.id_compte) AND (messages.supprime_destinataire = FALSE) AND (messages.id_destinataire = :id_destinataire)";

            $requette = $this->connexion->prepare($sql); 

            $requette->execute(array('id_destinataire' => $id_compte)); 

            return $requette; 
        }
    }
?>