<?php

echo "<link rel='stylesheet' href='gifts-styles.css'>";
echo "<link rel='stylesheet' href='snowfall.css'>";

require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);

// Fetch gifts, letters, and grades
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$grades = $db->query("SELECT student_id, grade FROM grades")->fetchAll();

// Calculate average grades for each child
$average_grades = [];
foreach ($grades as $grade) {
    $student_id = $grade['student_id'];
    $grade_value = $grade['grade'];

    if (!isset($average_grades[$student_id])) {
        $average_grades[$student_id] = ['total' => 0, 'count' => 0];
    }

    $average_grades[$student_id]['total'] += $grade_value;
    $average_grades[$student_id]['count']++;
}

// Calculate the average for each child
foreach ($average_grades as $student_id => $data) {
    $average_grades[$student_id]['average'] = $data['total'] / $data['count'];
}

// Count wishes only for children with an average grade of 5 or above
$wish_count = [];

foreach ($letters as $letter) {
    $student_id = $letter['sender_id']; // Assuming the letter has a sender_id field
    $average = isset($average_grades[$student_id]) ? $average_grades[$student_id]['average'] : 0;

    // Only process letters from children with an average grade of 5 or above
    if ($average >= 5) {
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
}

// Display gifts and their availability
echo "<div class='snowfall'></div>";
echo "<div class='container'>"; // Start container
echo "<h1>🎁 Dāvanu pieejamība 🎁</h1>"; // Title
echo "<ol>"; // Use ordered list
foreach ($gifts as $gift) {
    $count_available = $gift["count_available"];
    $count_wanted = isset($wish_count[$gift['id']]) ? $wish_count[$gift['id']] : 0;

    echo "<li><strong>" . $gift["name"] . " - " . $count_available . "</strong><br>";

    // Check if the stock is sufficient
    if ($count_available < $count_wanted) {
        echo "Šo dāvanu vēlas " . $count_wanted . " bērns(i) " . " - <span style='color: red;'>Pietrūkst!</span><br><br>";
    } elseif ($count_available > $count_wanted) {
        echo "Šo dāvanu vēlas " . $count_wanted . " bērns(i) " . " - <span style='color: green;'>Pietiek!</span><br><br>";
    } else {
        echo "Šo dāvanu vēlas " . $count_wanted . " bērns(i) " . " - <span style='color: orange;'>Tieši pietiek!</span><br><br>";
    }

    echo "</li>";
}
echo "</ol>";
echo "</div>";

?>

<script src='snowfall.js'></script>