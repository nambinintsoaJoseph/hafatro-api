<?php 
     /* +-------------------------------------------------------------------------------+
        | Ce fichier contient l'ensemble de code permettant d'envoyer un message        |
        | Date de création : samedi 14 février 2024                                     |
        | Auteur : RAZANAKANAMBININTSOA Joseph                                          |
        | Clé : email, id_expediteur, contenu                                           |
        +-------------------------------------------------------------------------------+
    */

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
        $informations_message = json_decode(file_get_contents("php://input"));  

        if( !empty($informations_message->adresse_email) and !empty($informations_message->id_expediteur) && !empty($informations_message->contenu) ) {
             // Récuperation de l'id du destinataire a partir de l'adresse email : 

             $sql = "SELECT id_compte FROM compte_utilisateur WHERE adresse_email = ?"; 
             $req = $connexion_base_de_donnee->prepare($sql); 
             $req->execute(array($informations_message->adresse_email));
             $id_destinataire = $req->fetch();
             // echo $id_destinataire['id_compte'];  

            $message->id_destinataire = $id_destinataire['id_compte']; 
            $message->id_expediteur = $informations_message->id_expediteur; 
            $message->contenu = $informations_message->contenu; 

            if($message->envoyerMessage($informations_message->adresse_email)) {
                http_response_code(201); 
                echo json_encode(["message" => "Votre message a été bien envoyé"]); 
            } else {
                echo json_encode(["message" => "Votre message n'a pas été envoyé"]); 
            }
        } else {
            echo json_encode([
                "message" => "Données invalide", 
            ]); 
        }
    } else {
        http_response_code(405); 
        echo json_encode([
            "message" => "La méthode n'est pas accepté", 
        ]); 
    }
?>