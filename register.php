<?php 
require 'includes/db.php';
require 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Basic Validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Check if email or username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password, $role])) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

require 'includes/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="text-center mb-4">Register</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">I am a...</label>
                    <select name="role" class="form-select" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">Already have an account? Login</a>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
