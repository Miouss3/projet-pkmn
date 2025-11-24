<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de récupération des catégories</h1>";
echo "Chemin du fichier : " . __DIR__ . "<br><br>";

try {
    // ÉTAPE 1 : Connexion à la base de données
    echo "<h2>Étape 1 : Connexion BDD</h2>";
    
    // MODIFIEZ CES VALEURS selon votre configuration
    $host = 'mysql';
    $dbname = 'bdd_pksa';  // ← CHANGEZ ICI
    $username = 'root';             // ← CHANGEZ ICI si besoin
    $password = 'root';                 // ← CHANGEZ ICI si vous avez un mot de passe
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base '$dbname' réussie<br><br>";
    
    // ÉTAPE 2 : Test SQL direct
    echo "<h2>Étape 2 : Test SQL direct</h2>";
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        echo "❌ <strong>La table 'categories' est VIDE</strong><br>";
        echo "Vous devez d'abord insérer des catégories dans votre base de données.<br><br>";
    } else {
        echo "✅ " . count($results) . " catégorie(s) trouvée(s) directement en SQL<br>";
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }
    
    // ÉTAPE 3 : Test avec le modèle Category
    echo "<h2>Étape 3 : Test avec le modèle Category</h2>";
    
    // Charger le modèle
    if (!file_exists(__DIR__ . '/../models/Category.php')) {
        throw new Exception("❌ Le fichier Category.php n'existe pas à : " . __DIR__ . '/../models/Category.php');
    }
    
    require_once __DIR__ . '/../models/Category.php';
    echo "✅ Fichier Category.php chargé<br>";
    
    // Utiliser le modèle
    $categoryModel = new App\Models\Category($pdo);
    $categories = $categoryModel->getAll();
    
    if (empty($categories)) {
        echo "❌ <strong>Le modèle retourne AUCUNE catégorie</strong><br>";
    } else {
        echo "✅ " . count($categories) . " catégorie(s) récupérée(s) via le modèle<br>";
        echo "<pre>";
        print_r($categories);
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<h2 style='color:green;'>✅ TOUT FONCTIONNE !</h2>";
    echo "<p>Vos catégories sont bien récupérées. Le problème est ailleurs (probablement dans le contrôleur ou les routes).</p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ ERREUR DE CONNEXION</h2>";
    echo "<p style='color:red;'><strong>Message :</strong> " . $e->getMessage() . "</p>";
    echo "<p>Vérifiez vos informations de connexion (host, dbname, username, password)</p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ ERREUR</h2>";
    echo "<pre style='color:red;'>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>