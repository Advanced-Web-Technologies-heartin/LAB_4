<?php
require __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    respond(["success" => false, "errors" => ["Only POST requests are allowed."]], 405);
}

$name = clean($_POST["name"] ?? "");
$enrollment = clean($_POST["enrollment"] ?? "");
$email = clean($_POST["email"] ?? "");
$course = clean($_POST["course"] ?? "");
$gender = clean($_POST["gender"] ?? "");
$marksRaw = $_POST["marks"] ?? "";

$errors = [];
if ($name === "" || mb_strlen($name) > 100) $errors[] = "Enter a name (maximum 100 characters).";
if ($enrollment === "" || mb_strlen($enrollment) > 40) $errors[] = "Enter a valid enrollment number.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) $errors[] = "Enter a valid email address.";
if (!in_array($course, ["B.Tech CSE", "BCA", "MCA", "B.Sc IT"], true)) $errors[] = "Please select a valid course.";
if (!in_array($gender, ["Male", "Female", "Other"], true)) $errors[] = "Please select gender.";
if ($marksRaw === "" || filter_var($marksRaw, FILTER_VALIDATE_INT) === false || (int)$marksRaw < 0 || (int)$marksRaw > 100) {
    $errors[] = "Marks must be a whole number between 0 and 100.";
}
if ($errors) respond(["success" => false, "errors" => $errors], 422);

$students = load_students();
foreach ($students as $student) {
    if (strcasecmp($student["enrollment"], $enrollment) === 0) {
        respond(["success" => false, "errors" => ["This enrollment number is already registered."]], 409);
    }
}

$students[] = [
    "enrollment" => $enrollment,
    "name" => $name,
    "email" => $email,
    "course" => $course,
    "gender" => $gender,
    "marks" => (int)$marksRaw
];

if (!save_students($students)) {
    respond(["success" => false, "errors" => ["Could not write to students.json. Check folder permissions."]], 500);
}

respond(["success" => true, "message" => "Student " . $name . " saved successfully!"]);
