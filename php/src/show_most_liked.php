<?php 

function afficherValeurOuDefaut($valeur, $nomDuChamp) {
    echo $valeur ? $valeur : "L'information pour $nomDuChamp n'est pas renseignée.";
}

function show_most_liked($conn) {
    $sql = 'SELECT f.id_oeuvre, o.*, COUNT(f.id_oeuvre) AS id_o
    FROM favoris f
    INNER JOIN oeuvre o ON  f.id_oeuvre = o.id_oeuvre
    GROUP BY f.id_oeuvre 
    ORDER BY id_o DESC 
    LIMIT 9';
    $statement =$conn->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>