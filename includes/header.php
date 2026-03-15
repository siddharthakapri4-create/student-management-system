<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); border-radius: 0.5rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>index.php"><i class="fa-solid fa-graduation-cap"></i> SRMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isLoggedIn()): ?>
                        <?php if(getUserRole() == 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/manage_students.php">Students</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/manage_courses.php">Courses</a></li>
                        <?php elseif(getUserRole() == 'teacher'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>teacher/teacher_dashboard.php">Dashboard</a></li>
                        <?php elseif(getUserRole() == 'student'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>student/student_dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger ms-2" href="<?= BASE_URL ?>logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn btn-primary px-4 rounded-pill text-white" href="<?= BASE_URL ?>login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container pb-5 flex-grow-1">
