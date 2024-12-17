<?php

echo "<link rel='stylesheet' href='gifts-styles.css'>";

require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);

// Fetch gifts and letters
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();

$wish_count = [];


foreach ($letters as $letter) {
    // Split the letter text into words
    $letter_text = strtolower($letter['letter_text']);
    foreach ($gifts as $gift) {
        // Check if the gift is mentioned in the letter
        if (stripos($letter_text, strtolower($gift['name'])) !== false) {
            if (!isset($wish_count[$gift['id']])) {
                $wish_count[$gift['id']] = 0;
            }
            $wish_count[$gift['id']]++;
        }
    }
}

// Display gifts and their availability
echo "<div class='container'>"; // Start container
echo "<h1>🎁Dāvanu pieejamība🎁</h1>"; // Title
echo "<ol>"; // Use ordered list
foreach ($gifts as $gift) {
    $count_available = $gift["count_available"];
    $count_wanted = isset($wish_count[$gift['id']]) ? $wish_count[$gift['id']] : 0;

    echo "<li><strong>" . $gift["name"] . " - " . $count_available . "</strong><br>";

    // Check if the stock is sufficient
    if ($count_available < $count_wanted) {
        echo "Šo dāvanu vēlas " . $count_wanted . " bērni " . " - <span style='color: red;'>Pietrūkst!</span><br><br>";
    } elseif ($count_available > $count_wanted) {
        echo "Šo dāvanu vēlas <strong>" . $count_wanted . "</strong> bērni " . " - <span style='color: green;'>Pietiek!</span><br><br>";
    } else {
        echo "Šo dāvanu vēlas <strong>" . $count_wanted . "</strong> bērni " . " - <span style='color: orange;'>Tieši pietiek!</span><br><br>";
    }

    echo "</li>";
}
echo "</ol>";
echo "</div>";

?>