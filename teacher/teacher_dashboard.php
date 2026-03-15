<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireRole('teacher');

// Get teacher details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM teachers WHERE user_id = ?");
$stmt->execute([$user_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold"><i class="fa-solid fa-chalkboard-user me-2 text-primary"></i>Teacher Dashboard</h2>
        <p class="text-muted">Welcome, <?= htmlspecialchars($teacher['name'] ?? 'Teacher') ?>.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-body p-5 text-center">
                <i class="fa-solid fa-person-chalkboard fa-4x text-muted mb-3"></i>
                <h4 class="fw-bold">Teacher Portal Features Coming Soon</h4>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    This section will allow you to upload marks, manage attendance, and view the list of students enrolled in your subjects.
                </p>
                <div class="mt-4">
                    <span class="badge bg-light text-dark border p-2 shadow-sm rounded-pill"><i class="fa-solid fa-hammer me-1 text-warning"></i> Under Active Development</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
