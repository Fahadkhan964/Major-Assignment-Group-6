<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('teacher');

if (!isset($_GET['quiz_id'])) {
    redirect('index.php');
}

$quiz_id = (int)$_GET['quiz_id'];
$success = '';
$error = '';

// Check if quiz belongs to this teacher
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE quiz_id = ? AND creator_id = ?");
$stmt->execute([$quiz_id, $_SESSION['user_id']]);
$quiz = $stmt->fetch();

if (!$quiz) {
    die("Access Denied: You cannot modify this quiz.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $q_text = sanitize($_POST['question']);
    $op_a = sanitize($_POST['option_a']);
    $op_b = sanitize($_POST['option_b']);
    $op_c = sanitize($_POST['option_c']);
    $op_d = sanitize($_POST['option_d']);
    $correct = $_POST['correct'];

    if (empty($q_text) || empty($op_a) || empty($op_b) || empty($op_c) || empty($op_d) || empty($correct)) {
        $error = "All fields required";
    } else {
        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$quiz_id, $q_text, $op_a, $op_b, $op_c, $op_d, $correct])) {
            $success = "Question added successfully!";
        } else {
            $error = "Failed to add question.";
        }
    }
}

// Fetch existing questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Add Questions to: <span class="text-primary"><?php echo htmlspecialchars($quiz['title']); ?></span></h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">&larr; Back to Dashboard</a>
    </div>

    <!-- Add Question Form -->
    <div class="col-md-5">
        <div class="card p-4 sticky-top" style="top: 20px; z-index: 1;">
            <h5 class="mb-3">New Question</h5>
            <?php if ($success): ?>
                <div class="alert alert-success py-2"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Question Text</label>
                    <textarea name="question" class="form-control" rows="2" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Option A</label>
                        <input type="text" name="option_a" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Option B</label>
                        <input type="text" name="option_b" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Option C</label>
                        <input type="text" name="option_c" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Option D</label>
                        <input type="text" name="option_d" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correct Option</label>
                    <select name="correct" class="form-select" required>
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Question</button>
            </form>
        </div>
    </div>

    <!-- Existing Questions List -->
    <div class="col-md-7">
        <h5 class="mb-3">Existing Questions (<?php echo count($questions); ?>)</h5>
        <?php foreach ($questions as $index => $q): ?>
            <div class="card p-3 mb-2">
                <p class="fw-bold mb-1">Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></p>
                <div class="row small text-muted">
                    <div class="col-6">A: <?php echo htmlspecialchars($q['option_a']); ?></div>
                    <div class="col-6">B: <?php echo htmlspecialchars($q['option_b']); ?></div>
                    <div class="col-6">C: <?php echo htmlspecialchars($q['option_c']); ?></div>
                    <div class="col-6">D: <?php echo htmlspecialchars($q['option_d']); ?></div>
                </div>
                <div class="mt-2 badge bg-success align-self-start">Correct: <?php echo $q['correct_option']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
