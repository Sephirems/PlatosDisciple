<?php 

function afficherValeurOuDefaut($valeur, $nomDuChamp) {
    echo $valeur ? $valeur : "L'information pour $nomDuChamp n'est pas renseignée.";
}

function show_user_likes($conn, $idUtilisateur) {
    $sql = 'SELECT o.*, COUNT(f.id_oeuvre) AS id_o
    FROM favoris f
    INNER JOIN oeuvre o ON  f.id_oeuvre = o.id_oeuvre
    WHERE f.id_utilisateur = :user_id
    GROUP BY f.id_oeuvre 
    ORDER BY id_o DESC 
    LIMIT 12';
    $statement =$conn->prepare($sql);
    $statement->execute([':user_id' => $idUtilisateur]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>