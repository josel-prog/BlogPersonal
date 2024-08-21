<?php
include 'includes/db.php';
session_start();

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $sql = "SELECT posts.*, users.username, users.profile_image FROM posts
            JOIN users ON posts.author_id = users.id
            WHERE posts.id = $post_id";
    $result = $conn->query($sql);
    $post = $result->fetch_assoc();

    if (!$post) {
        echo "Post not found.";
        exit();
    }

    // Fetch comments
    $sql_comments = "SELECT comments.*, users.username, users.profile_image FROM comments
                     JOIN users ON comments.user_id = users.id
                     WHERE comments.post_id = $post_id
                     ORDER BY comments.created_at DESC";
    $comments_result = $conn->query($sql_comments);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_id'];

        $sql_insert_comment = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
        if ($conn->query($sql_insert_comment) === TRUE) {
            header("Location: view_post.php?id=$post_id");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <p class="text-muted">
        <?php if (!empty($post['profile_image'])): ?>
            <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" alt="Profile Image" class="user-image-small">
        <?php endif; ?>
        <?php echo htmlspecialchars($post['username']); ?>
    </p>

    <hr>

    <h3>Comentarios</h3>
    <?php while ($comment = $comments_result->fetch_assoc()): ?>
        <div class="media mb-4">
            <?php if (!empty($comment['profile_image'])): ?>
                <img src="<?php echo htmlspecialchars($comment['profile_image']); ?>" alt="Profile Image" class="mr-3 user-image-small">
            <?php endif; ?>
            <div class="media-body">
                <h5 class="mt-0"><?php echo htmlspecialchars($comment['username']); ?></h5>
                <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                <small class="text-muted"><?php echo htmlspecialchars($comment['created_at']); ?></small>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h4>Añadir un comentario</h4>
        <form method="POST" action="view_post.php?id=<?php echo $post_id; ?>">
            <div class="form-group">
                <textarea class="form-control" name="comment" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Comentar</button>
        </form>
    <?php else: ?>
        <p>Por favor <a href="login.php">Iniciar sesion</a> Para poder comentar en la publicacion.</p>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary">Regresar a Inicio</a>
</div>
<?php include 'includes/footer.php'; ?>
