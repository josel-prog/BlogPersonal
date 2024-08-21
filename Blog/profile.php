<?php
session_start();
include 'includes/db.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtiene la información del usuario desde la base de datos
$sql = "SELECT username, email, profile_image FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);
$profile_image = !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'path/to/default/image.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualiza el perfil si se envió el formulario
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploaded_file = $_FILES['profile_image'];
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($uploaded_file['name']);
        
        if (move_uploaded_file($uploaded_file['tmp_name'], $upload_file)) {
            // Actualiza la ruta de la imagen en la base de datos
            $sql_update = "UPDATE users SET profile_image='$upload_file' WHERE id='$user_id'";
            $conn->query($sql_update);
            $profile_image = $upload_file; // Actualiza la variable para mostrar la nueva imagen
        }
    }

    if (isset($_POST['username']) && isset($_POST['email'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        
        $sql_update = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";
        $conn->query($sql_update);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-image-small {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <a href="index.php" class="btn btn-secondary mb-3">Regresar a inicio</a>
        <h2>Perfil</h2>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Imagen de perfil:</label>
                <?php if (!empty($profile_image)): ?>
                    <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="user-image-small">
                <?php endif; ?>
                <input type="file" class="form-control-file" id="profile_image" name="profile_image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar perfil</button>
        </form>
    </div>
</body>
</html>
