<?php
require_once("include/settings_example.php");
require_once("include/mysqli.php");
$db = new Db();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $message = trim($_POST["message"] ?? '');

    if ($name && $email && $message) {
        $timestamp = date("Y-m-d H:i:s");

        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $message = str_replace(["\r", "\n", ";"], " ", htmlspecialchars($message));

        $sql = "INSERT INTO feedback (submitted_at, name, email, message) VALUES (" .
               $db->dbFix($timestamp) . "," .
               $db->dbFix($name) . "," .
               $db->dbFix($email) . "," .
               $db->dbFix($message) . ")";

        if ($db->dbQuery($sql)) {
            file_put_contents("feedback.csv", "$timestamp;$name;$email;$message\n", FILE_APPEND);
            header("Location: index.php?page=thanks");
            exit;
        } else {
            echo "<p>Viga andmete salvestamisel.</p>";
        }
    } else {
        echo "<p>Palun täida kõik väljad.</p>";
    }
} else {
    header("Location: index.php?page=contact");
    exit;
}
