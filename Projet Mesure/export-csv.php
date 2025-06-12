<?php
$servername = "localhost";
$username = "User";
$password = "Chaise40140*";
$dbname = "Mesure_De";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Impossible de se connecter : " . $conn->connect_error);
}

// Requête SQL
$requete_sql = "SELECT Timestamp, Valeur_Mesure FROM Donnee_Mesurer";
$resultat = $conn->query($requete_sql);

// Si on trouve des informations dans la BDD
if ($resultat->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="donnees.csv"');

    // Prépare un endroit pour écrire les données
    $fichier_sortie = fopen('php://output', 'w');

    // Première ligne du fichier CSV
    fputcsv($fichier_sortie, array('Timestamp', 'Valeur_Mesure'));

    // Remplissage du CSV avec les données
    while ($ligne = $resultat->fetch_assoc()) {
        fputcsv($fichier_sortie, array($ligne['Timestamp'], $ligne['Valeur_Mesure']));
    }

    fclose($fichier_sortie);
}

// Fermeture de la connexion
$conn->close();
?>
