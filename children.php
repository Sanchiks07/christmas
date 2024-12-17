<?php

echo "<link rel='stylesheet' href='styles.css'>";
require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);
$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();

$gift_names = array_column($gifts, "name");

// Card container
echo "<div class='card-container'>";

// Display data in card format
foreach ($children as $child) {
    echo "<div class='card'>";
    echo "<h3>🎄" . $child["firstname"] . " " . $child["middlename"] . " " . $child["surname"] . " - " . $child["age"] . "🎄</h3>";

    $child_letters = [];
    foreach ($letters as $letter) {
        if ($letter["sender_id"] == $child["id"]) {
            $child_letters[] = $letter;
        }
    }

    if (!empty($child_letters)) {
        echo "<br>🎁Vēstule:";
        foreach ($child_letters as $letter) {
            $highlighted_text = $letter['letter_text'];
            $wishes = [];

            foreach ($gift_names as $gift) {
                // Highlight the gift names in the letter text
                $highlighted_text = preg_replace("/\b" . preg_quote($gift, '/') . "\b/i", "<strong>" . $gift . "</strong>", $highlighted_text);

                if (stripos($letter['letter_text'], $gift) !== false) {
                    $wishes[] = htmlspecialchars($gift);
                }
            }
    
            echo "<div class='letter-card'>";
            // Only escape the letter text, not the HTML tags
            echo "<p>" . nl2br($highlighted_text) . "</p>"; // Display highlighted letter text
            echo "</div>";

            if (!empty($wishes)) {
                echo "<br>Vēlmju saraksts:";
                echo "<ul>";
                foreach ($wishes as $wish) {
                    echo "<li>$wish</li>";
                }
                echo "</ul>";
            }
        }
    }

    echo "</div>"; // Close card
}

echo "</div>"; // Close card container

?>