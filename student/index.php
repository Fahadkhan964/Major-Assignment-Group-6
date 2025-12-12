<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('student');

// Fetch all available quizzes
// Ideally, we might want to check if the student has already taken them, 
// using a LEFT JOIN with results table, but requirements just say "view available".
// We will show score if they already took it.

$stmt = $pdo->prepare("
    SELECT q.*, u.username as creator_name, r.score 
    FROM quizzes q 
    JOIN users u ON q.creator_id = u.id 
    LEFT JOIN results r ON q.quiz_id = r.quiz_id AND r.student_id = ?
    ORDER BY q.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$quizzes = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="mb-4">
    <h2>Student Dashboard</h2>
    <p class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here are the available quizzes.</p>
</div>

<div class="row">
    <?php foreach ($quizzes as $quiz): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($quiz['title']); ?></h5>
                    <p class="card-text text-muted">
                        <small>By: <?php echo htmlspecialchars($quiz['creator_name']); ?></small>
                    </p>
                    <p class="card-text">
                        Time Limit: <strong><?php echo $quiz['time_limit_minutes']; ?> minutes</strong>
                    </p>

                    <?php if ($quiz['score'] !== null): ?>
                        <div class="alert alert-info py-2">
                            Score: <?php echo $quiz['score']; ?>%
                        </div>
                        <button class="btn btn-secondary w-100" disabled>Completed</button>
                    <?php else: ?>
                        <a href="take_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-primary w-100">Take Quiz</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if (empty($quizzes)): ?>
        <div class="col-12">
            <div class="alert alert-warning">No quizzes available at the moment.</div>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>
