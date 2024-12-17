<?php

echo "<link rel='stylesheet' href='styles.css'>";
require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);
$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();

$gift_names = array_column($gifts, 'gift_name');

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
        
        foreach ($child_letters as $letter) {
            // Highlight wishes in the letter text
            $highlighted_text = $letter['letter_text'];
            foreach ($gift_names as $gift) {
                // Use a regex to highlight the gift names in the letter text
                $highlighted_text = preg_replace("/\b" . preg_quote($gift, '/') . "\b/i", "<strong style='color: red;'>$gift</strong>", $highlighted_text);
            }

            echo "<br>🎁Vēstule:";
            echo "<div class='letter-card'>";
            echo "<p>" . $letter['letter_text'] . "</p>";
            echo "</div>";

            echo "<h4>Vēlmju saraksts:</h4>";
            echo "<ul>";
            foreach ($gift_names as $gift) {
                if (stripos($letter['letter_text'], $gift) !== false) {
                    echo "<li>" . htmlspecialchars($gift) . "</li>"; // List each wish
                }
            }
            echo "</ul>";
        }
    };

    echo "</div>"; // Close card

}

echo "</div>"; // Close card container

?>