<?php
/**
 * register.php — Phase 3: Registration Form View (Week 12)
 * ----------------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Renders the student registration form and displays field-level validation
 * feedback returned by process.php via the PHP session.
 *
 * Flow (POST-Redirect-GET pattern):
 *   1. User visits register.php  → form is displayed (clean state).
 *   2. User submits the form     → browser POSTs to process.php.
 *   3. process.php validates:
 *        • Errors found  → stores errors in session, redirects back here.
 *        • All valid     → shows the confirmation page (no redirect).
 *   4. register.php reads errors from session and shows them inline,
 *      then clears the session data so a refresh does not replay them.
 */

session_start();
require_once __DIR__ . '/../src/FormValidator.php';

// Retrieve any validation errors and previously entered values from the session.
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old']    ?? [];

// Clear session data immediately — prevents errors reappearing on page refresh.
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | JKUAT ECE 2312</title>
    <style>
        /* ── Base ── */
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

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
            padding: 36px 40px;
            width: 100%;
            max-width: 480px;
        }

        /* ── Header ── */
        .card-header { margin-bottom: 28px; }
        .card-header h1 { font-size: 1.5rem; color: #1a3c5e; margin-bottom: 4px; }
        .card-header p  { font-size: 0.85rem; color: #666; }

        /* ── Form elements ── */
        .field        { margin-bottom: 18px; }
        label         { display: block; font-weight: bold; color: #333; margin-bottom: 5px; font-size: 0.9rem; }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.95rem;
            transition: border-color .2s;
        }
        input:focus { outline: none; border-color: #2980b9; }
        input.is-error { border-color: #c0392b; background: #fff8f8; }

        /* ── Error message ── */
        .error-msg {
            color: #c0392b;
            font-size: 0.82rem;
            margin-top: 5px;
        }

        /* ── Submit button ── */
        .btn-submit {
            display: block;
            width: 100%;
            padding: 11px;
            background: #1a3c5e;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 8px;
            transition: background .2s;
        }
        .btn-submit:hover { background: #2471a3; }

        /* ── Global error banner (shown when any field has an error) ── */
        .alert-error {
            background: #fdecea;
            border-left: 4px solid #c0392b;
            color: #922b21;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1>Student Registration</h1>
        <p>JKUAT &middot; ECE Year 3 Semester 2 &middot; ICS/ECE 2312</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-error">
            Please correct the highlighted fields below.
        </div>
    <?php endif; ?>

    <!--
        POST to process.php — keeps validation logic out of the view.
        The action path is relative to the web/ directory.
    -->
    <form method="POST" action="process.php" novalidate>

        <!-- ── Name ── -->
        <div class="field">
            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="e.g. Grace Wanjiku"
                class="<?= isset($errors['name']) ? 'is-error' : '' ?>"
                autocomplete="name"
            >
            <?php if (isset($errors['name'])): ?>
                <div class="error-msg" role="alert">
                    <?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── Email ── -->
        <div class="field">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="e.g. grace@students.jkuat.ac.ke"
                class="<?= isset($errors['email']) ? 'is-error' : '' ?>"
                autocomplete="email"
            >
            <?php if (isset($errors['email'])): ?>
                <div class="error-msg" role="alert">
                    <?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── Age ── -->
        <div class="field">
            <label for="age">Age</label>
            <input
                type="number"
                id="age"
                name="age"
                value="<?= htmlspecialchars((string)($old['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                placeholder="18 – 100"
                min="18"
                max="100"
                class="<?= isset($errors['age']) ? 'is-error' : '' ?>"
            >
            <?php if (isset($errors['age'])): ?>
                <div class="error-msg" role="alert">
                    <?= htmlspecialchars($errors['age'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Register</button>
    </form>
</div>
</body>
</html>
