<?php 

    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('../../model/BaseDeDonnee.php'); 
        include_once('../../model/Messages.php'); 

        $base_de_donnee = new BaseDeDonnee(); 
        $connexion_base_de_donnee = $base_de_donnee->recupererConnexion(); 

        $message = new Messages($connexion_base_de_donnee); 
        $informationMessage = json_decode(file_get_contents("php://input")); 

        if( !empty($informationMessage->id_compte) ) {
            $stmt = $message->listerMessageRecu($informationMessage->id_compte); 
            
            if($stmt->rowCount() > 0) {
                // Si on a au moins un message : 
                $messageRecu = []; 
                while($donneeMessage = $stmt->fetch()) {
                    /* $mgs = [
                        "id_message" => $donneeMessage['id_message'], 
                        "date_envoi" => $donneeMessage['date_envoi'], 
                        "id_expediteur" => $donneeMessage['id_expediteur'], 
                        "id_destinataire" => $donneeMessage['id_destinataire'], 
                        "contenu" => $donneeMessage['contenu'], 
                        "supprime_expediteur" => $donneeMessage['supprime_expediteur'], 
                        "supprime_destinataire" => $donneeMessage['supprime_destinataire']
                    ]; */
		    
                    $mgs = [
                        "date_envoi" => $donneeMessage['date_envoi'], 
                        "id_expediteur" => $donneeMessage['id_expediteur'], 
                        "contenu" => $donneeMessage['contenu'],
			"id_message" => $donneeMessage['id_message']
                    ];

                    array_push($messageRecu, $mgs); 
                }

                http_response_code(201);
                echo json_encode($messageRecu); 
            }
        }
        else {
            echo json_encode(["message" => "Information vide"]); 
        }
    }
    else {
        http_response_code(405); 
        echo json_encode(["message" => "La méthode n'est pas accepté"]); 
    }
?>