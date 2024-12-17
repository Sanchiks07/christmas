<?php

echo    "<style>
            body {
                background-color: #368BC1;
                color: white;
                font-size: 20px;
                font-family: Consolas;
            }
        </style>";

require "Database.php";

$config = require ("config.php");

$db = new Database($config["database"]);
$gifts = $db->query("SELECT * FROM gifts")->fetchAll(PDO::FETCH_ASSOC);

// Ar foreach izvadīt content
echo "<ol>";
foreach ($gifts as $gift) {
    echo "<li>" . $gift["name"] . " - " . $gift["count_available"] . "</li>";
};
echo "</ol>";

?>