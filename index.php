<?php require_once 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h2>Welcome to Our School</h2>
        <p>Empowering students with knowledge and skills for the future</p>
        <div class="hero-buttons">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn btn-outline">Register</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="feature">
        <i class="fas fa-chalkboard-teacher"></i>
        <h3>Expert Teachers</h3>
        <p>Our qualified teachers provide the best education.</p>
    </div>
    <div class="feature">
        <i class="fas fa-book"></i>
        <h3>Quality Education</h3>
        <p>Comprehensive curriculum for all-round development.</p>
    </div>
    <div class="feature">
        <i class="fas fa-laptop"></i>
        <h3>Digital Learning</h3>
        <p>Modern technology integrated with traditional teaching.</p>
    </div>
</section>

<section class="announcements">
    <h2>Latest Announcements</h2>
    <div class="announcement-list">
        <?php
        $query = "SELECT * FROM notices ORDER BY post_date DESC LIMIT 3";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="announcement">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p>' . nl2br(htmlspecialchars(substr($row['content'], 0, 150))) . '...</p>';
                echo '<small>' . date('M d, Y', strtotime($row['post_date'])) . '</small>';
                echo '</div>';
            }
        } else {
            echo '<p>No announcements yet.</p>';
        }
        ?>
    </div>
    <a href="#" class="btn">View All Announcements</a>
</section>

<?php require_once 'includes/footer.php'; ?>