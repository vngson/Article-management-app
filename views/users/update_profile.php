<?php
require_once 'controllers/UserController.php';

$userController = new UserController();

// Retrieve author information based on session user_id
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$authorInfo = $userController->getAuthorInfo($user_id);

// Set default values for form fields
$full_name = $authorInfo['full_name'] ?? '';
$website = $authorInfo['website'] ?? '';
$bio = isset($authorInfo['bio']) ? $authorInfo['bio'] : '';
$interests = isset($authorInfo['interests']) ? $authorInfo['interests'] : '';
$education = isset($authorInfo['education']) ? $authorInfo['education'] : '';
$work_experiences = isset($authorInfo['work_experiences']) ? $authorInfo['work_experiences'] : '';
$image_path = $authorInfo['image_path'] ?? '/assets/images/default_avatar.png';
?>

<div class="view_paper" style="max-width: 700px;">
    <h2 class="login__title"><i class="fas fa-user-edit"></i> Update Your Profile</h2>
    
    <form action="index.php?action=update_profile" method="post" enctype="multipart/form-data" style="width: 100%;">
        <div class="form-group">
            <label for="full_name"><i class="fas fa-user"></i> Full Name *</label>
            <input type="text" id="full_name" name="full_name" placeholder="Your full name" value="<?= htmlspecialchars($full_name) ?>" required>
        </div>

        <div class="form-group">
            <label for="website"><i class="fas fa-globe"></i> Website</label>
            <input type="url" id="website" name="website" placeholder="https://yourwebsite.com" value="<?= htmlspecialchars($website) ?>">
        </div>

        <div class="form-group">
            <label for="bio"><i class="fas fa-pen-fancy"></i> Bio</label>
            <textarea id="bio" name="bio" placeholder="Tell us about yourself..." style="min-height: 100px;"><?= htmlspecialchars($bio) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="interests"><i class="fas fa-lightbulb"></i> Research Interests</label>
            <textarea id="interests" name="interests" placeholder="e.g., Machine Learning, Data Science, AI..." style="min-height: 100px;"><?= htmlspecialchars($interests) ?></textarea>
        </div>

        <div class="form-group">
            <label for="education"><i class="fas fa-graduation-cap"></i> Education</label>
            <textarea id="education" name="education" placeholder="Your educational background..." style="min-height: 80px;"><?= htmlspecialchars($education) ?></textarea>
        </div>

        <div class="form-group">
            <label for="work_experiences"><i class="fas fa-briefcase"></i> Work Experience</label>
            <textarea id="work_experiences" name="work_experiences" placeholder="Your professional experience..." style="min-height: 80px;"><?= htmlspecialchars($work_experiences) ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary btn-search" style="width: 100%; margin-top: 20px;">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </form>
</div>
