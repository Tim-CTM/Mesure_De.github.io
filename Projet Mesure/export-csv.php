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

if ($result->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="donnees.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, array('Timestamp', 'Valeur_Mesure'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, array($row['Timestamp'], $row['Valeur_Mesure']));
    }

    fclose($output);
} else {
    echo "Aucune donnée à exporter.";
}
$conn->close();
?>
