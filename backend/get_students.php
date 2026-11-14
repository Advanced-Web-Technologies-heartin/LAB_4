<?php
require __DIR__ . "/config.php";

$students = load_students();
foreach ($students as &$student) {
    $student["grade"] = grade((int)$student["marks"]);
}
unset($student);

$count = count($students);
$average = $count
    ? round(array_sum(array_column($students, "marks")) / $count, 1)
    : 0;

respond([
    "count" => $count,
    "average" => $average,
    "students" => $students
]);
