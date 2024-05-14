<?php

function afficherValeurOuDefaut($valeur, $nomDuChamp) {
    echo $valeur ? $valeur : "L'information pour $nomDuChamp n'est pas renseignée.";
}

function check_like_status($conn, $idUtilisateur, $idOeuvre) {
    $sql = 'SELECT * FROM Favoris WHERE id_utilisateur = :idu AND id_oeuvre = :ido';
    $statement = $conn->prepare($sql);
    $statement->execute([
        ':idu' => $idUtilisateur,
        ':ido' => $idOeuvre
    ]);

    return $statement->fetch();
}

function show_most_liked($conn) {
    $sql = 'SELECT f.id_oeuvre, o.*, COUNT(f.id_oeuvre) AS id_o
    FROM Favoris f
    INNER JOIN Oeuvre o ON  f.id_oeuvre = o.id_oeuvre
    GROUP BY f.id_oeuvre 
    ORDER BY id_o DESC 
    LIMIT 9';
    $statement =$conn->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function show_user_likes($conn, $idUtilisateur) {
    $sql = 'SELECT o.*, COUNT(f.id_oeuvre) AS id_o
    FROM Favoris f
    INNER JOIN Oeuvre o ON  f.id_oeuvre = o.id_oeuvre
    WHERE f.id_utilisateur = :user_id
    GROUP BY f.id_oeuvre 
    ORDER BY id_o DESC 
    LIMIT 12';
    $statement = $conn->prepare($sql);
    $statement->execute([':user_id' => $idUtilisateur]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function show_user_data($conn, $idUtilisateur) {
    $sql = 'SELECT nom_utilisateur, email_utilisateur, numero_de_telephone, date_de_naissance
    FROM Utilisateur u
    WHERE u.id_utilisateur = :user_id';
    $statement = $conn->prepare($sql);
    $statement->execute([':user_id' => $idUtilisateur]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>