<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireRole('admin');

// Fetch counts
$studentCount = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();
$courseCount = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$teacherCount = $conn->query("SELECT COUNT(*) FROM teachers")->fetchColumn();

require_once '../includes/header.php';
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold"><i class="fa-solid fa-gauge-high me-2 text-primary"></i>Admin Dashboard</h2>
        <p class="text-muted">Welcome back, Admin. Here is the overview of the system.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Students</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $studentCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-user-graduate fa-2x text-gray-300" style="color:#dddfeb;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="manage_students.php" class="text-primary text-decoration-none small fw-bold">View Details <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Courses</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $courseCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-book fa-2x text-gray-300" style="color:#dddfeb;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="manage_courses.php" class="text-success text-decoration-none small fw-bold">View Details <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Teachers</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $teacherCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-chalkboard-user fa-2x text-gray-300" style="color:#dddfeb;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <span class="text-warning text-decoration-none small fw-bold">Manage Teachers (Pending)</span>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
