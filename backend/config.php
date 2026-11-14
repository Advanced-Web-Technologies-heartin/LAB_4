<?php
// Shared backend settings and helper functions.
header("Content-Type: application/json; charset=UTF-8");

define("DATA_FILE", __DIR__ . "/data/students.json");

function load_students(): array {
    if (!file_exists(DATA_FILE)) return [];
    $json = file_get_contents(DATA_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function save_students(array $students): bool {
    $directory = dirname(DATA_FILE);
    if (!is_dir($directory)) mkdir($directory, 0775, true);
    return file_put_contents(
        DATA_FILE,
        json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

function grade(int $marks): string {
    if ($marks >= 90) return "A+";
    if ($marks >= 80) return "A";
    if ($marks >= 70) return "B";
    if ($marks >= 60) return "C";
    return "D";
}

function clean($value): string {
    return trim((string)($value ?? ""));
}

function respond(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
