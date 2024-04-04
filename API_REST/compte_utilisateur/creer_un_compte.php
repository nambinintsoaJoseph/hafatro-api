<?php 

    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('../../model/BaseDeDonnee.php'); 
        include_once('../../model/CompteUtilisateur.php'); 

        $base_de_donnee = new BaseDeDonnee(); 
        $connexion_base_de_donnee = $base_de_donnee->recupererConnexion(); 

        $compte = new CompteUtilisateur($connexion_base_de_donnee); 
        $informations_nouveau_compte = json_decode(file_get_contents("php://input")); 
        
        $information_valide = !empty($informations_nouveau_compte->nom_utilisateur) && !empty($informations_nouveau_compte->adresse_email) && !empty($informations_nouveau_compte->mot_de_passe); 

        if($information_valide) {
            $compte->nom_utilisateur = $informations_nouveau_compte->nom_utilisateur; 
            $compte->adresse_email = $informations_nouveau_compte->adresse_email; 
            $compte->mot_de_passe = password_hash($informations_nouveau_compte->mot_de_passe, PASSWORD_DEFAULT); 

            if($compte->creerUnCompte()) {
                http_response_code(201); 
                echo json_encode(["message" => "La création du compte a été effectué"]); 
            } else {
                echo json_encode(["message" => "La création du compte n'a pas été effectué"]); 
            }
        } else {
            echo json_encode(["message" => "Données invalide"]); 

        }
    } else {
        http_response_code(405); 
        echo json_encode(["message" => "La méthode n'est pas accepté"]); 
    }
?>