<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    $role = getUserRole();
    if ($role == 'admin') header("Location: " . BASE_URL . "admin/dashboard.php");
    elseif ($role == 'teacher') header("Location: " . BASE_URL . "teacher/teacher_dashboard.php");
    elseif ($role == 'student') header("Location: " . BASE_URL . "student/student_dashboard.php");
    exit;
}
?>

<div class="row align-items-center mb-5" style="min-height: 70vh;">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold mb-4">Manage Student Records with Ease</h1>
        <p class="lead text-muted mb-4">A complete digital solution to manage students, courses, marks, and attendance securely.</p>
        <a href="<?= BASE_URL ?>login.php" class="btn btn-primary btn-lg shadow-sm rounded-pill px-5 py-3">Get Started <i class="fa-solid fa-arrow-right ms-2"></i></a>
    </div>
    <div class="col-md-6 text-center mt-5 mt-md-0">
        <!-- SVG illustration via unDraw/Popsy (placeholder) -->
        <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Student Vector" class="img-fluid" style="max-height: 400px;">
    </div>
</div>

<div class="row text-center mt-5">
    <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100">
            <h1 class="text-primary"><i class="fa-solid fa-users"></i></h1>
            <h4 class="fw-bold mt-2">Centralized Data</h4>
            <p class="text-muted">Manage all student and teacher records in one secure place.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100">
            <h1 class="text-success"><i class="fa-solid fa-chart-line"></i></h1>
            <h4 class="fw-bold mt-2">Analytics</h4>
            <p class="text-muted">Interactive dashboards and visual statistics.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100">
            <h1 class="text-warning"><i class="fa-solid fa-lock"></i></h1>
            <h4 class="fw-bold mt-2">Secure Roles</h4>
            <p class="text-muted">Role-based access controls for Admin, Teachers, and Students.</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
