<?php 
require 'includes/db.php';
require 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'teacher') {
            redirect('admin/index.php');
        } else {
            redirect('student/index.php');
        }
    } else {
        $error = "Invalid email or password.";
    }
}

require 'includes/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 mt-5">
            <h3 class="text-center mb-4">Login</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php" class="text-decoration-none">Don't have an account? Register</a>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
