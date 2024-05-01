<?php

function check_like_status($conn, $idUtilisateur, $idOeuvre) {
    $sql = 'SELECT * FROM favoris WHERE id_utilisateur = :idu AND id_oeuvre = :ido';
    $statement = $conn->prepare($sql);
    $statement->execute([
        ':idu' => $idUtilisateur,
        ':ido' => $idOeuvre
    ]);

    return $statement->fetch();
}
?>