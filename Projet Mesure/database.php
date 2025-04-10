<?php

$servername = "192.168.17.10";
$username = "root";
$password = "lamp";
$dbname = "Mesure_De";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$sql = "SELECT Timestamp, Valeur_Mesure FROM Donnee_Mesurer";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "timestamp" => $row["Timestamp"],
            "valeur" => $row["Valeur_Mesure"]
        );
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();

?>
