<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. Lecture JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['identifiant'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe manquant.']);
    exit;
}

$identifiant = $data['identifiant'];
$password_clair = $data['password'];

// 2. Connexion à la BDD
$servername = "192.168.17.10";
$username_db = "root";
$password_db_conn = "lamp";
$dbname = "Mesure_De";

$conn = new mysqli($servername, $username_db, $password_db_conn, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit;
}

// 3. Requête SQL
$sql = "SELECT Mot_de_passe, Cle_Chiffrement FROM Client_Web WHERE ID_Client_PK = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $identifiant);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe incorrect.']);
    exit;
}

$row = $result->fetch_assoc();
$db_password_b64 = $row['Mot_de_passe'];
$key_hex = $row['Cle_Chiffrement'];

$key = hex2bin($key_hex);
if ($key === false || strlen($key) !== 16) {
    echo json_encode(['success' => false, 'message' => 'Clé de chiffrement invalide.']);
    exit;
}

// 4. Déchiffrement et comparaison
$password_input_encrypted_b64 = encrypt($password_clair, $key);

$db_password_decrypted = decrypt($db_password_b64, $key);
$input_password_decrypted = decrypt($password_input_encrypted_b64, $key);

if ($db_password_decrypted !== false && $db_password_decrypted === $input_password_decrypted) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe incorrect.']);
}

$stmt->close();
$conn->close();

// 🔐 Fonctions AES-128-CBC
function encrypt($plain, $key) {
    $iv = 'myiv123456789012'; // IV FIXE (16 octets)
    if (strlen($key) !== 16) return false;
    $encrypted = openssl_encrypt($plain, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $encrypted ? base64_encode($encrypted) : false;
}

function decrypt($encrypted_b64, $key) {
    $iv = 'myiv123456789012';
    if (strlen($key) !== 16) return false;
    $encrypted = base64_decode($encrypted_b64);
    $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $decrypted !== false ? $decrypted : false;
}
?>
