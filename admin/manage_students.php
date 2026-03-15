<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireRole('admin');

$success = '';
$error = '';

// Handle add student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course_id = $_POST['course_id'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $error = "Name, Email, Username, and Password are required.";
    } else {
        try {
            $conn->beginTransaction();
            
            // 1. Create user account
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
            $stmt->execute([$username, $hashed_password]);
            
            $user_id = $conn->lastInsertId();
            
            // 2. Create student record
            $stmt = $conn->prepare("INSERT INTO students (user_id, name, email, phone, course_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $email, $phone, $course_id ?: null]);
            
            $conn->commit();
            $success = "Student added successfully.";
        } catch(PDOException $e) {
            $conn->rollBack();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "Username or Email already exists.";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Handle delete student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Find user_id first to delete the user account (which will cascade to student via DB constraint if set, but let's be safe)
        $stmt = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$student['user_id']]);
            $success = "Student deleted successfully.";
        }
    } catch(PDOException $e) {
        $error = "Cannot delete student.";
    }
}

// Fetch students with course names
$query = "SELECT s.*, c.course_name, u.username 
          FROM students s 
          LEFT JOIN courses c ON s.course_id = c.id
          LEFT JOIN users u ON s.user_id = u.id 
          ORDER BY s.created_at DESC";
$students = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses for dropdown
$courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fa-solid fa-user-graduate me-2 text-primary"></i>Manage Students</h2>
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom text-primary fw-bold">
                <i class="fa-solid fa-user-plus me-1"></i> Add New Student
            </div>
            <div class="card-body">
                <form action="manage_students.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <h6 class="text-muted border-bottom pb-2 mb-3">Student Identity</h6>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="e.g. john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +1 234 567 8900">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Select Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- No Course Assigned --</option>
                            <?php foreach($courses as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Login Credentials</h6>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="For portal access">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Initial password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">Add Student</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom fw-bold text-dark">
                <i class="fa-solid fa-users me-1"></i> Enrolled Students List
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Contact</th>
                                <th>Course</th>
                                <th>Username</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($students)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted"> <i class="fa-solid fa-user-xmark mb-2 fa-2x"></i><br> No students found.</td></tr>
                            <?php else: ?>
                                <?php foreach($students as $s): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">
                                            <?= htmlspecialchars($s['name']) ?>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><i class="fa-regular fa-envelope text-muted"></i> <?= htmlspecialchars($s['email']) ?></div>
                                                <?php if($s['phone']): ?>
                                                    <div><i class="fa-solid fa-phone text-muted"></i> <?= htmlspecialchars($s['phone']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($s['course_name']): ?>
                                                <span class="badge bg-info text-dark shadow-sm"><?= htmlspecialchars($s['course_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">Not assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><code class="text-dark bg-light px-2 py-1 rounded border"><?= htmlspecialchars($s['username']) ?></code></td>
                                        <td class="text-end pe-3">
                                            <a href="manage_students.php?delete=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Are you sure you want to delete this student? All their records will be removed.');" title="Delete Student"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
