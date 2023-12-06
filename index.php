<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
    <!-- Ajout de Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Ajout d'un style personnalisé pour le fond de page -->
    <style>
        body {
            background: linear-gradient(to right, #FF6363, #007BFF);
        }
    </style>
</head>
<body class="p-8">

<?php
// Charger la configuration depuis le fichier JSON
$config = json_decode(file_get_contents('config.json'), true);

// Informations de connexion à la base de données
$db_host = $config['db_host'];
$db_user = $config['db_user'];
$db_password = $config['db_password'];
$db_name = $config['db_name'];

// Connexion à la base de données
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Initialisation de la variable d'erreur
$erreur = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire avec la fonction validateInput
    $nom = validateInput($_POST["nom"], "/^[a-zA-Z'àÀâéÉèÈêÊëîïôœûùüçÇ^¨]+$/");
    $prenom = validateInput($_POST["prenom"], "/^[a-zA-Z'àÀâéÉèÈêÊëîïôœûùüçÇ^¨]+$/");
    $entreprise = validateInput($_POST["entreprise"], "/^[a-zA-Z0-9\s'àÀâéÉèÈêÊëîïôœûùüçÇ^¨&]+$/");
    $fonction = validateInput($_POST["fonction"], "/^[a-zA-Z\s'àÀâéÉèÈêÊëîïôœûùüçÇ^¨]+$/");
    $email = validateInput($_POST["email"], "/^[a-zA-Z0-9._-àÀâéÉèÈêÊëîïôœûùüçÇ^¨]+@[a-zA-Z0-9.-àÀâéÉèÈêÊëîïôœûùüçÇ^¨]+\.[a-zA-Z]{2,4}$/");

    // Vérifier que les données sont définies
    if ($nom !== false && $prenom !== false && $entreprise !== false && $fonction !== false && $email !== false) {
        // Utiliser des requêtes préparées pour éviter les injections SQL
        $stmt = $conn->prepare("INSERT INTO user (lastName, firstName, company, job, email) VALUES (?, ?, ?, ?, ?)");

        // Liage des paramètres
        $stmt->bind_param("sssss", $nom, $prenom, $entreprise, $fonction, $email);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Rediriger vers la confirmation si l'insertion est réussie
            header("Location: confirm.php");
            exit();
        } else {
            $erreur = "Erreur lors de l'insertion dans la base de données : " . $stmt->error;
        }

        // Fermer la déclaration préparée
        $stmt->close();
    } else {
        // Gérer les erreurs spécifiques pour chaque champ
        if ($nom === false) {
            $erreur .= "Veuillez entrer un nom de famille valide. ";
        }

        if ($prenom === false) {
            $erreur .= "Veuillez entrer un prénom valide. ";
        }

        if ($entreprise === false) {
            $erreur .= "Veuillez entrer un nom d'entreprise valide. ";
        }

        if ($fonction === false) {
            $erreur .= "Veuillez entrer une fonction valide. ";
        }

        if ($email === false) {
            $erreur .= "Veuillez entrer une adresse email valide. ";
        }
    }
}

// Fonction pour valider les entrées avec une expression régulière
function validateInput($input, $pattern) {
    $trimmedInput = trim($input);
    // Utilisation de htmlspecialchars lors du retour de la valeur
    if (preg_match($pattern, $trimmedInput)) {
        return htmlspecialchars($trimmedInput);
    } else {
        return false;
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>



<div class="max-w-md mx-auto bg-white p-8 rounded-md shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Formulaire d'inscription</h2>
    <!-- Affichage du message d'erreur -->
    <?php if (!empty($erreur)): ?>
        <div class="mb-4 text-red-500"><?php echo $erreur; ?></div>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <!-- Pré-remplissage des champs avec les données soumises en cas d'erreur -->
        <div class="mb-4">
            <label for="nom" class="block text-sm font-semibold text-gray-600">Nom de famille:</label>
            <input type="text" name="nom" id="nom" class="border p-2 w-full" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
        </div>

        <div class="mb-4">
            <label for="prenom" class="block text-sm font-semibold text-gray-600">Prénom:</label>
            <input type="text" name="prenom" id="prenom" class="border p-2 w-full" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
        </div>

        <div class="mb-4">
            <label for="entreprise" class="block text-sm font-semibold text-gray-600">Nom de l'entreprise:</label>
            <input type="text" name="entreprise" id="entreprise" class="border p-2 w-full" value="<?php echo isset($_POST['entreprise']) ? htmlspecialchars($_POST['entreprise']) : ''; ?>" required>
        </div>

        <div class="mb-4">
            <label for="fonction" class="block text-sm font-semibold text-gray-600">Fonction du poste:</label>
            <input type="text" name="fonction" id="fonction" class="border p-2 w-full" value="<?php echo isset($_POST['fonction']) ? htmlspecialchars($_POST['fonction']) : ''; ?>" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-gray-600">Email:</label>
            <input type="email" name="email" id="email" class="border p-2 w-full" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        </div>

        <input type="submit" value="Soumettre" class="bg-blue-500 text-white p-2 rounded-md cursor-pointer hover:bg-blue-700">
    </form>
</div>

</body>
</html>
