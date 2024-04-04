<?php 
     /* +-------------------------------------------------------------------------------+
        | Ce fichier contient l'ensemble de code pour modifier le nom de l'utilisateur  |
        | Date de création : samedi 14 février 2024                                     |
        | Auteur : RAZANAKANAMBININTSOA Joseph                                          |
        | Clé d'entrée : id_compte, nom_utilisateur                                     |
        +-------------------------------------------------------------------------------+
    */

    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

    if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        include_once('../../model/BaseDeDonnee.php'); 
        include_once('../../model/CompteUtilisateur.php'); 

        $base_de_donnee = new BaseDeDonnee(); 
        $connexion_base_de_donnee = $base_de_donnee->recupererConnexion(); 

        $compte = new CompteUtilisateur($connexion_base_de_donnee); 
        $nouvelle_informations = json_decode(file_get_contents("php://input")); 
        
        $information_valide = !empty($nouvelle_informations->id_compte) && !empty($nouvelle_informations->nom_utilisateur);

        if($information_valide) {
            $compte->id_compte = $nouvelle_informations->id_compte; 
            $compte->nom_utilisateur = $nouvelle_informations->nom_utilisateur; 

            if($compte->modifierNomUtilisateur()) {
                http_response_code(201); 
                echo json_encode(["message" => "La modification de votre nom a été effectué"]); 
            } else {
                echo json_encode(["message" => "La modification de votre nom n'a pas été effectué"]); 
            }
        } else {
            echo json_encode(["message" => "Données invalide"]); 

        }
    } else {
        http_response_code(405); 
        echo json_encode(["message" => "La méthode n'est pas accepté"]); 
    }
?>