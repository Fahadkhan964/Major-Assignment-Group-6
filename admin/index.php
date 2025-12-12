<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('teacher');

// Fetch quizzes created by this teacher
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE creator_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$quizzes = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Teacher Dashboard</h2>
    <a href="add_quiz.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Create New Quiz</a>
</div>

<div class="card p-4">
    <h4>My Quizzes</h4>
    <?php if (empty($quizzes)): ?>
        <p class="text-muted mt-3">You haven't created any quizzes yet.</p>
    <?php else: ?>
        <div class="table-responsive mt-3">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Time Limit (min)</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?php echo $quiz['quiz_id']; ?></td>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo $quiz['time_limit_minutes']; ?> mins</td>
                            <td><?php echo date('M d, Y', strtotime($quiz['created_at'])); ?></td>
                            <td>
                                <a href="add_question.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-sm btn-info text-white">Add Questions</a>
                                <!-- Extend functionalities as needed: Edit, Delete, View Results specific to quiz -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>
