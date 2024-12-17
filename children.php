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
$children = $db->query("SELECT * FROM children")->fetchAll(PDO::FETCH_ASSOC);

// Ar foreach izvadīt content
echo "<ul>";
foreach ($children as $child) {
    echo "<li>" . $child["firstname"] . " " . $child["middlename"] . " " . $child["surname"] . " - " . $child["age"] . "</li>";
};
echo "</ul>";

?>