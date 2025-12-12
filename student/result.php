<?php
require '../includes/db.php';
require '../includes/functions.php';
checkRole('student');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$quiz_id = (int)$_POST['quiz_id'];
$total_questions = (int)$_POST['total_questions'];
$student_id = $_SESSION['user_id'];
$score = 0;

// Prevent double submission handled in index.php check, but good to check here too
$stmt = $pdo->prepare("SELECT id FROM results WHERE student_id = ? AND quiz_id = ?");
$stmt->execute([$student_id, $quiz_id]);
if ($stmt->fetch()) {
    die("You have already taken this quiz.");
}

// Fetch Correct Answers
$stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$correct_answers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [id => correct_option]

// Calculate Score
foreach ($correct_answers as $q_id => $correct_opt) {
    if (isset($_POST["q_$q_id"]) && $_POST["q_$q_id"] === $correct_opt) {
        $score++;
    }
}

// Calculate Percentage
$percentage = ($total_questions > 0) ? ($score / $total_questions) * 100 : 0;

// Store Result
$stmt = $pdo->prepare("INSERT INTO results (student_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)");
$stmt->execute([$student_id, $quiz_id, $percentage, $total_questions]);

require '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 text-center mt-5">
        <div class="card p-5">
            <h2 class="mb-4">Quiz Result</h2>
            
            <div class="display-1 fw-bold <?php echo $percentage >= 50 ? 'text-success' : 'text-danger'; ?>">
                <?php echo round($percentage, 2); ?>%
            </div>
            
            <p class="lead mt-3">
                You got <strong><?php echo $score; ?></strong> out of <strong><?php echo $total_questions; ?></strong> questions correct.
            </p>

            <a href="index.php" class="btn btn-primary mt-4">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
