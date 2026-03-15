<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireRole('admin');

$success = '';
$error = '';

// Handle add course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $duration = trim($_POST['duration']);
    
    if (empty($course_name) || empty($course_code)) {
        $error = "Course name and code are required.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, duration) VALUES (?, ?, ?)");
            $stmt->execute([$course_name, $course_code, $duration]);
            $success = "Course added successfully.";
        } catch(PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "Course code already exists.";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Handle delete course
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Course deleted successfully.";
    } catch(PDOException $e) {
        $error = "Cannot delete course because it has associated records (e.g., enrolled students).";
    }
}

// Fetch courses
$courses = $conn->query("SELECT * FROM courses ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fa-solid fa-book me-2 text-primary"></i>Manage Courses</h2>
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
                <i class="fa-solid fa-plus-circle me-1"></i> Add New Course
            </div>
            <div class="card-body">
                <form action="manage_courses.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Course Name</label>
                        <input type="text" name="course_name" class="form-control" required placeholder="e.g. Bachelor of Science">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Course Code</label>
                        <input type="text" name="course_code" class="form-control" required placeholder="e.g. BSC01">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Duration</label>
                        <input type="text" name="duration" class="form-control" placeholder="e.g. 3 Years">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">Add Course</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom fw-bold text-dark">
                <i class="fa-solid fa-list me-1"></i> Course Directory
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Code</th>
                                <th>Course Name</th>
                                <th>Duration</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($courses)): ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted"> <i class="fa-solid fa-folder-open mb-2 fa-2x"></i><br> No courses found.</td></tr>
                            <?php else: ?>
                                <?php foreach($courses as $c): ?>
                                    <tr>
                                        <td class="ps-3"><span class="badge bg-secondary"><?= htmlspecialchars($c['course_code']) ?></span></td>
                                        <td class="fw-bold"><?= htmlspecialchars($c['course_name']) ?></td>
                                        <td><?= htmlspecialchars($c['duration']) ?></td>
                                        <td class="text-end pe-3">
                                            <a href="manage_courses.php?delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this course?');" title="Delete Course"><i class="fa-solid fa-trash"></i></a>
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
