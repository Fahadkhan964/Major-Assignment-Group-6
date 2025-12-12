<?php
// Note: functions.php should be included before header in pages to handle sessions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Examination System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo strpos($_SERVER['PHP_SELF'], 'admin') !== false || strpos($_SERVER['PHP_SELF'], 'student') !== false ? '../css/style.css' : 'css/style.css'; ?>">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-mortarboard-fill"></i> ExamSys
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'teacher'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="../admin/index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../admin/add_quiz.php">Create Quiz</a></li>
                    <?php elseif ($_SESSION['role'] === 'student'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="../student/index.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2 px-3" href="../logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
