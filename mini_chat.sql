CREATE TABLE IF NOT EXISTS compte_utilisateur(
    id_compte BIGINT(11) NOT NULL AUTO_INCREMENT, 
    nom_utilisateur VARCHAR(40) NOT NULL, 
    adresse_email VARCHAR(30) NOT NULL, 
    mot_de_passe VARCHAR(255) NOT NULL,
    PRIMARY KEY(id_compte)
) ENGINE=InnoDB; 

CREATE TABLE IF NOT EXISTS messages(
    id_message BIGINT(11) NOT NULL AUTO_INCREMENT, 
    date_envoi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    id_expediteur BIGINT(11) NOT NULL, 
    id_destinataire BIGINT(11) NOT NULL, 
    contenu TEXT NOT NULL, 
    supprime_expediteur BOOLEAN NOT NULL DEFAULT FALSE, 
    supprime_destinataire BOOLEAN NOT NULL DEFAULT FALSE, 
    CONSTRAINT fk_compte_id_destinataire FOREIGN KEY(id_destinataire) REFERENCES compte_utilisateur(id_compte),
    CONSTRAINT fk_compte_id_expediteur FOREIGN KEY(id_expediteur) REFERENCES compte_utilisateur(id_compte), 
    PRIMARY KEY(id_message)
) ENGINE=InnoDB; 