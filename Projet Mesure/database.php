<?php
$servername = "192.168.17.10";
$username = "root";
$password = "lamp";
$dbname = "Mesure_De";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("La connexion a échoué : " . $conn->connect_error);
}

$sql = "SELECT Timestamp, Valeur_Mesure FROM Donnee_Mesurer";

//Exécution de la requête SQL
$result = $conn->query($sql);

//Tableau pour stocker les totaux par jour
$sumPerDays = array();

// Vérification si il y a des résultats
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc())
    {
        $timestamp = $row["Timestamp"];
        $valeur = floatval($row["Valeur_Mesure"]);
        $datePart = substr($timestamp, 0, 10); //Les 10 premiers caractère donc la date ex : 2025-05-12

        // Addition des valeurs pour chaque jour
        if (isset($sumPerDays[$datePart])) {
            $sumPerDays[$datePart] += $valeur;
        } else {
            $sumPerDays[$datePart] = $valeur;
        }
    }
}

//Tableau de sortie au format JSON
$output = array();
foreach ($sumPerDays as $date => $sum) {
    $output[] = array(
        "timestamp" => $date, //Nouveau tableau associatif
        "valeur" => $sum //La somme des mesures pour cette date
    );
}

header('Content-Type: application/json');
echo json_encode($output);

$conn->close();
?>