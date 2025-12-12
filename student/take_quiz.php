<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('student');

if (!isset($_GET['quiz_id'])) {
    redirect('index.php');
}

$quiz_id = (int)$_GET['quiz_id'];
$student_id = $_SESSION['user_id'];

// Check if already taken
$stmt = $pdo->prepare("SELECT id FROM results WHERE student_id = ? AND quiz_id = ?");
$stmt->execute([$student_id, $quiz_id]);
if ($stmt->fetch()) {
    // Already taken
    redirect('index.php');
}

// Fetch Quiz info
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch Questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 sticky-top bg-white p-3 shadow-sm" style="top: 0; z-index: 1000;">
    <div>
        <h3 class="m-0"><?php echo htmlspecialchars($quiz['title']); ?></h3>
    </div>
    <div class="text-end">
        <span class="text-muted small">Time Remaining:</span>
        <div id="timer" class="quiz-timer">--:--</div>
    </div>
</div>

<form id="quizForm" action="result.php" method="POST">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
    <input type="hidden" name="total_questions" value="<?php echo count($questions); ?>">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (empty($questions)): ?>
                <div class="alert alert-warning">No questions found for this quiz.</div>
            <?php else: ?>
                <?php foreach ($questions as $index => $q): ?>
                    <div class="card question-card p-4">
                        <h5 class="mb-3"><?php echo ($index + 1) . ". " . htmlspecialchars($q['question_text']); ?></h5>
                        
                        <div class="mb-2">
                            <input type="radio" class="btn-check" name="q_<?php echo $q['id']; ?>" id="q<?php echo $q['id']; ?>_A" value="A" required>
                            <label class="option-label" for="q<?php echo $q['id']; ?>_A">A. <?php echo htmlspecialchars($q['option_a']); ?></label>
                        </div>
                        <div class="mb-2">
                            <input type="radio" class="btn-check" name="q_<?php echo $q['id']; ?>" id="q<?php echo $q['id']; ?>_B" value="B">
                            <label class="option-label" for="q<?php echo $q['id']; ?>_B">B. <?php echo htmlspecialchars($q['option_b']); ?></label>
                        </div>
                        <div class="mb-2">
                            <input type="radio" class="btn-check" name="q_<?php echo $q['id']; ?>" id="q<?php echo $q['id']; ?>_C" value="C">
                            <label class="option-label" for="q<?php echo $q['id']; ?>_C">C. <?php echo htmlspecialchars($q['option_c']); ?></label>
                        </div>
                        <div class="mb-2">
                            <input type="radio" class="btn-check" name="q_<?php echo $q['id']; ?>" id="q<?php echo $q['id']; ?>_D" value="D">
                            <label class="option-label" for="q<?php echo $q['id']; ?>_D">D. <?php echo htmlspecialchars($q['option_d']); ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-5">Submit Quiz</button>
            <?php endif; ?>
        </div>
    </div>
</form>

<!-- Pass PHP time limit to JS -->
<script>
    const TIME_LIMIT_MINUTES = <?php echo $quiz['time_limit_minutes']; ?>;
</script>
<script src="../js/script.js"></script>

<?php require '../includes/footer.php'; ?>
