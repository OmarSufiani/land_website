<?php
session_start();
include '../databases/db.php';

// ✅ Ensure logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// ✅ Fetch current details
$stmt = $conn->prepare("SELECT firstname, lastname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ✅ Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);

    if (empty($firstname) || empty($lastname) || empty($email)) {
        $message = "<div class='alert alert-danger text-center'>All fields except password are required.</div>";
    } else {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $firstname, $lastname, $email, $hashed, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?");
            $stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);
        }

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Profile updated successfully!</div>";
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname']  = $lastname;
            $_SESSION['email']     = $email;
        } else {
            $message = "<div class='alert alert-danger text-center'>Error updating profile.</div>";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <a href="dashboard.php" class="btn btn-sm btn-outline-primary mb-3">&larr; Back to Dashboard</a>
    <div class="card shadow p-4 rounded-4">
    
        <h3 class="text-center mb-4 text-success">Edit Profile</h3>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Update Profile</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
