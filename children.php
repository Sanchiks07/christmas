<?php

echo "<link rel='stylesheet' href='styles.css'>";
require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);
$children = $db->query("SELECT * FROM children")->fetchAll(PDO::FETCH_ASSOC);

// Card container
echo "<div class='card-container'>";

// Display data in card format
foreach ($children as $child) {
    echo "<div class='card'>";
    echo "<h3>" .$child["firstname"] . " " . $child["middlename"] . " " . $child["surname"] . " - " . $child["age"] . "</h3>";
    echo "</div>";
}

echo "</div>"; // Close card container

?>