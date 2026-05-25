<?php
/**
 * process.php — Phase 3: Form POST Processor (Week 12)
 * ------------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Receives the registration form submission from register.php,
 * validates every field using FormValidator, and either:
 *   (a) Redirects back to register.php with field-level errors in the session,
 *       or
 *   (b) Displays the "Registration Successful" confirmation page.
 *
 * Security note:
 *   All user-supplied values are passed through htmlspecialchars() before
 *   being echoed to the browser.  This prevents accidental HTML injection
 *   (XSS) regardless of what the user submitted.
 *   Never echo $_POST values directly.
 */

session_start();
require_once __DIR__ . '/../src/FormValidator.php';

// ── Guard: only process genuine POST requests ─────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Direct GET access — send the user back to the form.
    header('Location: register.php');
    exit;
}

// ── Collect and sanitise raw POST input ──────────────────────────────────────
// We trim strings and cast age to int here so the validator receives clean values.
$input = [
    'name'  => trim((string)($_POST['name']  ?? '')),
    'email' => trim((string)($_POST['email'] ?? '')),
    'age'   => (int)($_POST['age'] ?? 0),
];

// ── Run server-side validation ────────────────────────────────────────────────
$validator = new FormValidator();
$errors    = $validator->validateAll($input);

if (!empty($errors)) {
    // Validation failed — store errors and old values in the session so
    // register.php can display them, then redirect back (POST-Redirect-GET).
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $input;

    header('Location: register.php');
    exit;
}

// ── All fields are valid — prepare safe output values ─────────────────────────
// htmlspecialchars() converts <, >, &, ", ' to HTML entities.
// ENT_QUOTES ensures both single and double quotes are escaped.
$safeName  = htmlspecialchars($input['name'],          ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($input['email'],         ENT_QUOTES, 'UTF-8');
$safeAge   = htmlspecialchars((string)$input['age'],   ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful | JKUAT ECE 2312</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
            padding: 36px 40px;
            width: 100%;
            max-width: 480px;
        }

        .success-icon { font-size: 2.5rem; margin-bottom: 12px; }

        h1 { color: #1e8449; font-size: 1.5rem; margin-bottom: 16px; }

        .detail-row {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
            color: #333;
        }
        .detail-row:last-of-type { border-bottom: none; }
        .label { font-weight: bold; color: #555; width: 70px; display: inline-block; }

        .btn-back {
            display: inline-block;
            margin-top: 24px;
            padding: 9px 20px;
            background: #1a3c5e;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background .2s;
        }
        .btn-back:hover { background: #2471a3; }
    </style>
</head>
<body>
<div class="card">
    <div class="success-icon">&#10003;</div>
    <h1>Registration Successful</h1>

    <!--
        Expected output format as specified in the project brief:
            <h1>Registration Successful</h1>
            <p>Name: Grace Wanjiku</p>
            <p>Email: grace.wanjiku@students.jkuat.ac.ke</p>
            <p>Age: 21</p>

        All values are passed through htmlspecialchars() above — safe to echo.
    -->
    <p>Name: <?= $safeName ?></p>
    <p>Email: <?= $safeEmail ?></p>
    <p>Age: <?= $safeAge ?></p>

    <a href="register.php" class="btn-back">&larr; Register another student</a>
</div>
</body>
</html>
