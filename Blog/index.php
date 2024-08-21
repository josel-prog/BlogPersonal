<?php
include 'includes/db.php';

$sql = "SELECT posts.*, users.username, users.profile_image FROM posts
        JOIN users ON posts.author_id = users.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<?php include 'includes/header.php'; ?>
<div class="row">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="<?php echo htmlspecialchars($row['profile_image']); ?>" class="card-img-top user-image-small" alt="Profile Image">
                <div class="card-body">
                    <h5 class="card-title"><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h5>
                    <p class="card-text"><?php echo substr($row['content'], 0, 200); ?>...</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <?php echo htmlspecialchars($row['username']); ?>
                        </small>
                    </p>
                    <a href="view_post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Leer Publicación</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php include 'includes/footer.php'; ?>
