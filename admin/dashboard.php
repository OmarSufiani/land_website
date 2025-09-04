<?php
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['firstname'] ?? 'User'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">My Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text me-3 text-light">Welcome, <?= htmlspecialchars($user_name) ?>!</span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container my-5">
  <h2 class="text-center mb-4">Dashboard</h2>
  <div class="row g-4">

    <!-- Example Button 1 -->
    <div class="col-md-4">
      <a href="orders.php" class="btn btn-success w-100 p-4 rounded-4 shadow-lg">
        <i class="fas fa-user fa-2x mb-2"></i><br>
        orders
      </a>
    </div>

    <!-- Example Button 2 -->
    <div class="col-md-4">
      <a href="add_plot.php" class="btn btn-success w-100 p-4 rounded-4 shadow-lg">
        <i class="fas fa-cog fa-2x mb-2"></i><br>
        Add new Plot
      </a>
    </div>

    <!-- Example Button 3 -->
    <div class="col-md-4">
      <a href="settings.php" class="btn btn-success w-100 p-4 rounded-4 shadow-lg">
        <i class="fas fa-chart-line fa-2x mb-2"></i><br>
        Settings
      </a>
    </div>

  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
