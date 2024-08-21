<?php
include 'includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (title, content, author_id) VALUES ('$title', '$content', '$author_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Post created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<?php include 'includes/header.php'; ?>
<h2>Crear una nueva Publicacion</h2>
<form method="POST" action="create_post.php">
    <div class="form-group">
        <label for="title">Titulo:</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="content">Contenido:</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Crear Publicacion</button>
</form>
<?php include 'includes/footer.php'; ?>
