<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Valores predeterminados
$username = '';
$profile_image = 'path/to/default/image.jpg'; // Imagen por defecto

// Verifica si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Recupera el nombre de usuario y la imagen del perfil desde la base de datos
    $sql_user = "SELECT username, profile_image FROM users WHERE id='$user_id'";
    $result_user = $conn->query($sql_user);
    if ($result_user && $user = $result_user->fetch_assoc()) {
        $username = htmlspecialchars($user['username']);
        $profile_image = !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'path/to/default/image.jpg';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Entre Letras</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .navbar {
            background-color: #fff; /* Color de fondo blanco para la navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            width: 100px; /* Ajusta el tamaño del logo según sea necesario */
            height: auto;
        }
        .navbar-nav {
            align-items: center;
        }
        .nav-link {
            color: #333; /* Color de los enlaces */
            font-size: 1rem;
        }
        .nav-link:hover {
            color: #007bff; /* Color del enlace al pasar el mouse */
        }
        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-item {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="index.php">
        <img src="img/ranura.jpeg" alt="Logo"> <!-- Asegúrate de poner la ruta correcta del logo -->
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="create_post.php">Crear Publicación</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $username; ?>
                        <img src="<?php echo $profile_image; ?>" alt="User Image" class="user-image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">Perfil</a>
                        <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                    </div>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Registrarse</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Iniciar sesión</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container">