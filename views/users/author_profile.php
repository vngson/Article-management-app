<?php
require_once 'controllers/UserController.php';

$userController = new UserController();

// Get author info based on email (you may modify this as per your application logic)
$user_id = isset($_GET['author_id']) ? $_GET['author_id'] : $_SESSION['user_id']; // Example: $_GET['email'] is passed via URL or session
$authorInfo = $userController->getAuthorInfo($user_id);

$image_path = $authorInfo['image_path'] ?? '/assets/images/default_avatar.png';

?>

<div class="profile">
    <div style="text-align: center; flex-shrink: 0;">
        <img src='/assets/images/default_avatar.png' alt="profile_avt" class="profile_avt">
        <h2 style="margin-top: 15px; color: #1f2937; font-weight: 700; font-size: 2rem;"><?= $authorInfo['full_name'] ?></h2>
        <p style="color: #6b7280; font-size: 1.3rem; margin: 5px 0 0 0;">
            <i class="fas fa-user-graduate"></i> Researcher
        </p>
    </div>
    
    <div class="profile__info">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                <strong style="color: #6366f1;"><i class="fas fa-pen-fancy"></i> Bio</strong>
                <p style="margin: 10px 0 0 0; font-size: 1.4rem; color: #374151;">
                    <?= $authorInfo['bio'] ?: 'No bio provided' ?>
                </p>
            </div>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                <strong style="color: #6366f1;"><i class="fas fa-lightbulb"></i> Interests</strong>
                <p style="margin: 10px 0 0 0; font-size: 1.4rem; color: #374151;">
                    <?= $authorInfo['interests'] ?: 'No interests provided' ?>
                </p>
            </div>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                <strong style="color: #6366f1;"><i class="fas fa-graduation-cap"></i> Education</strong>
                <p style="margin: 10px 0 0 0; font-size: 1.4rem; color: #374151;">
                    <?= $authorInfo['education'] ?: 'No education provided' ?>
                </p>
            </div>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                <strong style="color: #6366f1;"><i class="fas fa-briefcase"></i> Work Experience</strong>
                <p style="margin: 10px 0 0 0; font-size: 1.4rem; color: #374151;">
                    <?= $authorInfo['work_experiences'] ?: 'No work experience provided' ?>
                </p>
            </div>
        </div>
        
        <div class="profile_action" style="margin-bottom: 20px;">
            <a href="index.php?action=update_profile" class="profile_action-btn btn btn-primary">
                <i class="fas fa-edit"></i> Update Information
            </a>
            <a href="index.php?action=add_paper" class="profile_action-btn btn btn-primary">
                <i class="fas fa-file-upload"></i> Add Paper
            </a>
        </div>
        
        <h3 class="author_list_paper" style="margin-top: 30px;">
            <i class="fas fa-book"></i> Papers by <?= htmlspecialchars($authorInfo['full_name']) ?>
        </h3>
        <div class="home__paper_list" style="margin-top: 20px;">
            <?php foreach($authorInfo['papers'] as $paper): ?>
                <a href="index.php?action=view_paper&paper_id=<?php echo $paper['paper_id']; ?>" class="home__paper">
                    <h4 class="home__paper--title">
                        <?php echo htmlspecialchars($paper['title']); ?>
                    </h4>
                    <span class="home__paper--abstract">
                        <?php echo htmlspecialchars($paper['abstract']); ?>
                    </span>
                    <div style="padding: 15px; padding-top: 0; color: #6366f1; font-weight: 600; font-size: 1.3rem;">
                        Read more <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
