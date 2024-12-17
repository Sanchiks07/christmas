<?php

require "Database.php";

$db = new Database($config["database"]);
$kids = $db->query("SELECT * FROM children")->fetchAll(PDO::FETCH_ASSOC);

// Ar foreach izvadīt content
echo "<ul>";
foreach ($kids as $kid) {
    echo "<li>" . $kid["firstname, middlename, surname, age"] . "</li>";
};
echo "</ul>";

?>