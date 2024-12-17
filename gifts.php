<?php

require "Database.php";

$db = new Database($config["database"]);
$posts = $db->query("SELECT * FROM posts")->fetchAll(PDO::FETCH_ASSOC);

// Ar foreach izvadīt content
echo "<ul>";
foreach ($posts as $post) {
    echo "<li>" . $post["content"] . "</li>";
};
echo "</ul>";

?>