<?php

echo "<link rel='stylesheet' href='styles.css'>";
require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);
$children = $db->query("SELECT * FROM children")->fetchAll(PDO::FETCH_ASSOC);
$letters = $db->query("SELECT * FROM letters")->fetchAll(PDO::FETCH_ASSOC);

// Card container
echo "<div class='card-container'>";

// Display data in card format
foreach ($children as $child) {
    echo "<div class='card'>";
    echo "<h3>🎄" .$child["firstname"] . " " . $child["middlename"] . " " . $child["surname"] . " - " . $child["age"] . "🎄</h3>";

    $child_letters = [];
    foreach ($letters as $letter) {
        if ($letter["sender_id"] == $child["id"]) {
            $child_letters[] = $letter;
        }
    };

    if (!empty($child_letters)) {
        echo "🎁Vēstule:";
        foreach ($child_letters as $letter) {
            echo "<div class='letter-card'>";
            echo "<p>" . $letter['letter_text'] . "</p>";
            echo "</div>";
        }
    };

    echo "</div>";

}

echo "</div>"; // Close card container

?>