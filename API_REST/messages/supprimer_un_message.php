<?php 
     /* +-------------------------------------------------------------------------------+
        | Ce fichier contient l'ensemble de code pour supprimer un (seul) message       |
        | Date de création : samedi 14 février 2024                                     |
        | Auteur : RAZANAKANAMBININTSOA Joseph                                          |
        | Clé d'entrée : id_message, id_compte                                     |
        +-------------------------------------------------------------------------------+
    */

    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

    if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        include_once('../../model/BaseDeDonnee.php'); 
        include_once('../../model/Messages.php'); 

        $base_de_donnee = new BaseDeDonnee(); 
        $connexion_base_de_donnee = $base_de_donnee->recupererConnexion(); 

        $message = new Messages($connexion_base_de_donnee); 
        $information_suppression = json_decode(file_get_contents("php://input")); // donnée json réçu
        
        $information_valide = !empty($information_suppression->id_compte) && !empty($information_suppression->id_message);

        if($information_valide) {
            // Clé de l'API : 
            $message->id_compte = $information_suppression->id_compte; 
            $message->id_message = $information_suppression->id_message; 

            // On récupère l'id_expediteur
            $recuperer_id_expediteur = $connexion_base_de_donnee->prepare('SELECT id_expediteur FROM messages WHERE id_message = ?'); 
            $recuperer_id_expediteur->execute(array($message->id_message)); 
            $id_expediteur = $recuperer_id_expediteur->fetch(); 
            $id_expediteur = $id_expediteur['id_expediteur']; 

            if($message->supprimerUnMessage($message->id_compte, $id_expediteur, $message->id_message)) {
                http_response_code(201); 
                echo json_encode(["message" => "Ce message a été supprimé"]); 
            } else {
                echo json_encode(["message" => "Ce message n'a pas été supprimé"]); 
            }
        } else {
            echo json_encode(["message" => "Données invalide"]); 

        }
    } else {
        http_response_code(405); 
        echo json_encode(["message" => "La méthode n'est pas accepté"]); 
    }
?>