<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: " . BASE_URL . "index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white text-center py-4 border-bottom-0">
                <h3 class="mb-0 fw-bold">Sign In</h3>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-muted">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 rounded-pill fw-bold shadow-sm">Login</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted small mb-0">Default Admin Account</p>
                    <span class="badge bg-light text-dark shadow-sm">admin / admin123</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
