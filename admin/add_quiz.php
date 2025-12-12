<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('teacher');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $time = (int)$_POST['time'];

    if (empty($title) || $time < 1) {
        $error = "Please provide a valid title and time limit.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO quizzes (title, creator_id, time_limit_minutes) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $_SESSION['user_id'], $time])) {
            $quiz_id = $pdo->lastInsertId();
            header("Location: add_question.php?quiz_id=$quiz_id");
            exit();
        } else {
            $error = "Failed to create quiz.";
        }
    }
}

require '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h4 class="mb-3">Create New Quiz</h4>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Quiz Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. PHP Basics" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Time Limit (Minutes)</label>
                    <input type="number" name="time" class="form-control" min="1" value="10" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create & Add Questions</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
