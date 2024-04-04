<?php 

    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('../../model/BaseDeDonnee.php'); 
        include_once('../../model/CompteUtilisateur.php'); 

        $base_de_donnee = new BaseDeDonnee(); 
        $connexion_base_de_donnee = $base_de_donnee->recupererConnexion(); 

        $compte = new CompteUtilisateur($connexion_base_de_donnee); 

        $informationAuthentification = json_decode(file_get_contents("php://input")); 

        $informationValide = !empty($informationAuthentification->adresse_email) && !empty($informationAuthentification->mot_de_passe);

        if($informationValide) {
            $compte->adresse_email = $informationAuthentification->adresse_email; 
            $compte->mot_de_passe = $informationAuthentification->mot_de_passe; 

            $stmt = $compte->authentification(); 

            // On vérifie si on a au moins 1 compte utilisateur associé : 
            if($stmt->rowCount() > 0) {
                $donnee = $stmt->fetch();

                // On enregistre l'état du mot de passe dans une variable : 
                $etatMdp = password_verify($informationAuthentification->mot_de_passe, $donnee['mot_de_passe']) ? "true" : "false";

                $infoConnexion = [
                    'id_compte' => $donnee['id_compte'],
                    'nom_utilisateur' => $donnee['nom_utilisateur'], 
                    'adresse_email' => $donnee['adresse_email'],
                    'motdepassecorrect' => $etatMdp
                ]; 

                http_response_code(200); 
                echo json_encode($infoConnexion); 
            }
            else {
                echo json_encode(["message" => "Ce compte n'existe pas"]); 
            }

        }
        else {
            echo 'Info non valide'; 
        }

    } else {
        echo json_encode(["message" => "La méthode n'est pas accepté"]);
    }
?>