<?php
$servername = "192.168.17.10";
$username = "root";
$password = "lamp";
$dbname = "Mesure_De";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($connexion->connect_error) 
{
    die("Impossible de se connecter : " . $connexion->connect_error);
}

$requete_sql = "SELECT Timestamp, Valeur_Mesure FROM Donnee_Mesurer";
$resultat = $connexion->query($requete_sql);

// Si on trouve des informations dans la BDD .
if ($resultat->num_rows > 0) 
{
    header('Content-Type: text/csv'); //Telechargement CSV
    header('Content-Disposition: attachment; filename="donnees.csv"');

    //Prépare un endroit pour écrire les données
    $fichier_sortie = fopen('php://output', 'w');

    // Première ligne du fichier CSV noms des colonnes : 'Timestamp' et 'Valeur_Mesure'
    fputcsv($fichier_sortie, array('Timestamp', 'Valeur_Mesure'));

    // Pour chaque ligne dans la base de données qu'on trouve
    while ($ligne = $resultat->fetch_assoc()) 
    {
        //'Timestamp' et 'Valeur_Mesure' sont écrits dans le fichier CSV
        fputcsv($fichier_sortie, array($ligne['Timestamp'], $ligne['Valeur_Mesure']));
    }

    fclose($fichier_sortie);
}
$connexion->close();
?>