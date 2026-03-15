<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit;
}

// Get student details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT s.*, c.course_name 
    FROM students s 
    LEFT JOIN courses c ON s.course_id = c.id 
    WHERE s.user_id = ?
");
$stmt->execute([$user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold"><i class="fa-solid fa-user-graduate me-2 text-primary"></i>Student Dashboard</h2>
        <p class="text-muted">Welcome back, <?= htmlspecialchars($student['name'] ?? 'Student') ?>.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 border-top border-primary border-4 h-100">
            <div class="card-header bg-white py-3 border-bottom text-primary fw-bold">
                <i class="fa-solid fa-id-card me-1"></i> My Profile
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-user fa-2x text-primary"></i>
                    </div>
                </div>
                
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted small fw-bold" width="40%">Name</td>
                            <td class="fw-bold"><?= htmlspecialchars($student['name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-bold">Email</td>
                            <td><?= htmlspecialchars($student['email'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-bold">Phone</td>
                            <td><?= htmlspecialchars($student['phone'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-bold">Course</td>
                            <td>
                                <?php if(isset($student['course_name']) && $student['course_name']): ?>
                                    <span class="badge bg-info text-dark shadow-sm"><?= htmlspecialchars($student['course_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">Not Enrolled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100 bg-light">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-chart-bar fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">My Marks</h5>
                        <p class="text-muted small">View your academic performance and exam results.</p>
                        <button class="btn btn-outline-success btn-sm rounded-pill px-3 mt-2 disabled">Coming Soon</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100 bg-light">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-calendar-check fa-3x text-warning mb-3"></i>
                        <h5 class="fw-bold">Attendance</h5>
                        <p class="text-muted small">Track your daily class attendance records.</p>
                        <button class="btn btn-outline-warning btn-sm rounded-pill px-3 mt-2 disabled">Coming Soon</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
