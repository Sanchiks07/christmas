<?php

echo "<link rel='stylesheet' href='gifts-styles.css'>";

require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);

// Fetch gifts and letters
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();

// Create an array to count how many children want each gift
$wish_count = [];

// Count wishes for each gift
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
echo "<h1>Dāvanu pieejamība</h1>"; // Title
echo "<ol>"; // Use ordered list
foreach ($gifts as $gift) {
    $count_available = $gift["count_available"];
    $count_wanted = isset($wish_count[$gift['id']]) ? $wish_count[$gift['id']] : 0;

    echo "<li>" . htmlspecialchars($gift["name"]) . " - " . $count_available;

    // Check if the stock is sufficient
    if ($count_available < $count_wanted) {
        echo " - <span style='color: red;'>Trūkst!</span> (" . $count_wanted . " bērni vēlas)";
    } elseif ($count_available > $count_wanted) {
        echo " - <span style='color: green;'>Pietiek!</span> (" . $count_wanted . " bērni vēlas)";
    } else {
        echo " - <span style='color: orange;'>Tieši pietiek!</span> (" . $count_wanted . " bērni vēlas)";
    }

    echo "</li>";
}
echo "</ol>"; // Close ordered list
echo "</div>"; // End container

?>