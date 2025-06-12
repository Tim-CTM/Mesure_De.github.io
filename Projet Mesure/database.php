<?php
header('Content-Type: application/json');

$servername = "217.182.60.210";
$username = "etudiant";
$password = "admincielir";
$dbname = "Mesure_De"; 

$conn = new mysqli($servername, $username, $password, $dbname);
// Vérifie si la connexion a échoué
if ($conn->connect_error) {
    echo json_encode(['error' => 'Échec de la connexion à la base de données : ' . $conn->connect_error]);
    exit;
}
// Récupère l'ID du dispositif depuis les paramètres GET de l'URL
$selectedDeviceId = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;

$sql = "SELECT Timestamp, Valeur_Mesure FROM Donnee_Mesurer";

// Ajoute WHERE si un ID de dispositif est fourni
if ($selectedDeviceId !== null) {
    if ($selectedDeviceId == 1) {
        $sql .= " WHERE ID_Dispositif_FK = 1";
    } else {
        $sql .= " WHERE ID_Dispositif_FK != 1";
    }
}

$sql .= " ORDER BY Timestamp ASC";
// Execute requete SQL
$result = $conn->query($sql);

$sumPerDays = array();
// Vérifie si la requête a renvoyé des résultats
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timestamp = $row["Timestamp"];
        $valeur = floatval($row["Valeur_Mesure"]);
        $datePart = substr($timestamp, 0, 10);
        //Somme des valeurs par jour
        if (isset($sumPerDays[$datePart])) {
            $sumPerDays[$datePart] += $valeur;
        } else {
            $sumPerDays[$datePart] = $valeur;
        }
    }
}
// Prépare le tableau de sortie au format JSON
$output = array();
foreach ($sumPerDays as $date => $sum) {
    $output[] = array(
        "timestamp" => $date,
        "valeur" => $sum 
    );
}

echo json_encode($output);

$conn->close();
?>