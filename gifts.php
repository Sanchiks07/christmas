<?php

echo "<link rel='stylesheet' href='gifts-styles.css'>";
echo "<link rel='stylesheet' href='snowfall.css'>";

require "Database.php";
$config = require ("config.php");

$db = new Database($config["database"]);

$gifts = $db->query("SELECT * FROM gifts")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$grades = $db->query("SELECT student_id, grade FROM grades")->fetchAll();

// Aprēķina katra bērna vidējo
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

foreach ($average_grades as $student_id => $data) {
    $average_grades[$student_id]['average'] = $data['total'] / $data['count'];
}

// Aprēķina vēlmes
$wish_count = [];

foreach ($letters as $letter) {
    $student_id = $letter['sender_id'];
    $average = isset($average_grades[$student_id]) ? $average_grades[$student_id]['average'] : 0;

    // Saskaiti tās bērnu vēlmes, kuriem vidējā ir virs vai ir tieši 5
    if ($average >= 5) {
        // Sadala vēstuli pa vārdiem
        $letter_text = strtolower($letter['letter_text']);
        foreach ($gifts as $gift) {
            // Pārbauda vai dāva ir vēstulē
            if (stripos($letter_text, strtolower($gift['name'])) !== false) {
                if (!isset($wish_count[$gift['id']])) {
                    $wish_count[$gift['id']] = 0;
                }
                $wish_count[$gift['id']]++;
            }
        }
    }
}


echo "<div class='snowfall'></div>"; // sniega animācija
echo "<h1>🎁 Dāvanu saraksts 🎁</h1>";
echo "<div class='container'>";
echo "<ol>";
foreach ($gifts as $gift) {
    $count_available = $gift["count_available"];
    $count_wanted = isset($wish_count[$gift['id']]) ? $wish_count[$gift['id']] : 0;

    echo "<li><strong>" . $gift["name"] . " - " . $count_available . "</strong><br>";

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

echo "<br><div class='link'>";
echo "<a href='children.php'>Skatīt Bērnu Vēstules</a>";
echo "</div>";

?>

<script src='snowfall.js'></script>