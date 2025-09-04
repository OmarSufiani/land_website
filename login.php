<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'databases/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];

            $success = "Login successful! Redirecting...";
            // Redirect after 2 seconds
            echo "<meta http-equiv='refresh' content='2;url=admin/dashboard.php'>";
        } else {
            $error = "Invalid email or password. Please try again.";
        }

        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Jerzy Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .spinner-border {
        width: 1.3rem;
        height: 1.3rem;
        margin-left: 8px;
        display: none;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow p-4 rounded-4">
        <h2 class="text-center mb-4">Login</h2>

        <!-- ✅ Centered green spinner -->
        <div class="d-flex justify-content-center mb-3">
          <span class="spinner-border text-success"
                role="status"
                aria-hidden="true"
                id="loadingSpinner"
                style="display:none; width:2rem; height:2rem;">
          </span>
        </div>

        <!-- ✅ Messages -->
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>

          <button type="submit" id="loginBtn" class="btn btn-primary w-100">
            Login
          </button>
        </form>

        <div class="text-center mt-3">
          <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const form = document.getElementById('loginForm');
  const loginBtn = document.getElementById('loginBtn');
  const spinner = document.getElementById('loadingSpinner');

  form.addEventListener('submit', function (e) {
    e.preventDefault(); // Stop immediate submit
    loginBtn.disabled = true;
    spinner.style.display = 'inline-block';

    // Wait 3 seconds, then actually submit
    setTimeout(() => {
      form.submit();
    }, 3000);
  });
</script>


</body>
</html>
