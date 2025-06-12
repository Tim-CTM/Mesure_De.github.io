<?php
$servername = "localhost";
$username = "User";
$password = "Chaise40140*";
$dbname = "Mesure_De";

// Définir le type de contenu en JSON
header('Content-Type: application/json');

try
{
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données : " . $e->getMessage()]);
    exit;
}

// === Traitement du formulaire ===
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données du formulaire
    $idClient = $_POST['idClient'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

    // Vérification que les champs sont remplis
    if (empty($idClient) || empty($motDePasse))
    {
        echo json_encode(["success" => false, "message" => "Identifiant ou mot de passe manquant."]);
        exit;
    }

    // Recherche de l'utilisateur dans la base de données
    $sql = "SELECT Mot_de_passe, Salt FROM Client_Web WHERE ID_Client_PK = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $idClient]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user)
    {
        // Hachage du mot de passe en clair avec SHA-256 et le salt
        $hashedPassword = hash('sha256', $motDePasse . $user['Salt']);

        // Vérification du mot de passe : Comparer les deux hachages SHA-256
        if ($hashedPassword === $user['Mot_de_passe'])
        {
            // Si le mot de passe est correct
            echo json_encode(["success" => true, "message" => "Connexion réussie"]);
        }
        else
        {
            // Si le mot de passe est incorrect
            echo json_encode(["success" => false, "message" => "Mot de passe ou utilisateur incorrect."]);
        }
    }
    else
    {
        // Si l'utilisateur n'est pas trouvé
        echo json_encode(["success" => false, "message" => "Utilisateur non trouvé."]);
    }
}
?>
