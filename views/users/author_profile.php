<?php
require_once 'controllers/UserController.php';

$userController = new UserController();

// Get author info based on email (you may modify this as per your application logic)
$user_id = isset($_GET['author_id']) ? $_GET['author_id'] : $_SESSION['user_id']; // Example: $_GET['email'] is passed via URL or session
$authorInfo = $userController->getAuthorInfo($user_id);

$image_path = $authorInfo['image_path'] ?? '/assets/images/default_avatar.png';

?>

<div class="profile">
    <img src='/assets/images/default_avatar.png' alt="profile_avt" class="profile_avt">
    <div class="profile__info">
    <h3><?= $authorInfo['full_name'] ?></h3>
    <p><strong>Bio:</strong> <?= $authorInfo['bio'] ?></p>
    <p><strong>Interests:</strong> <?= $authorInfo['interests'] ?></p>
    <p><strong>Education:</strong> <?= $authorInfo['education'] ?></p>
    <p><strong>Work Experiences:</strong> <?= $authorInfo['work_experiences'] ?></p>
    
    <h3 class="author_list_paper">List of Papers</h3>
    <div class="home__paper_list">
        <?php foreach($authorInfo['papers'] as $paper): ?>
            <a href="index.php?action=view_paper&paper_id=<?php echo $paper['paper_id']; ?>" class="home__paper">
                <h4 class="home__paper--title">
                    <?php echo htmlspecialchars($paper['title']); ?>
                </h4>
                <span class="home__paper--abstract">
                    <?php echo htmlspecialchars($paper['abstract']); ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="profile_action">
        <a href="index.php?action=update_profile" class="profile_action-btn btn btn-primary">Update Information</a>
        <a href="index.php?action=add_paper" class="profile_action-btn btn btn-primary">Add paper</a>
    </div>
</div>
</div>