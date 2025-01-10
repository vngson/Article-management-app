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
$image_path = $authorInfo['image_path'] ?? '/assets/images/default_avatar.png';
?>

<div class="profile">
    <img src='/assets/images/default_avatar.png' alt="profile_avt" class="profile_avt">
    <div class="profile__info">
    <h2>Update Profile</h2>
    <form action="index.php?action=update_profile" method="post" enctype="multipart/form-data">
        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($full_name) ?>" required><br><br>

        <label for="website">Website:</label><br>
        <input type="text" id="website" name="website" value="<?= htmlspecialchars($website) ?>"><br><br>

        <label for="bio">Bio:</label><br>
        <textarea id="bio" name="bio" rows="4" cols="50"><?= htmlspecialchars($bio) ?></textarea><br><br>
        
        <label for="interests">Interests:</label><br>
        <textarea id="interests" name="interests" rows="4" cols="50"><?= htmlspecialchars($interests) ?></textarea><br><br>

        <input type="submit" name="submit" value="Update Profile">
    </form>

    </div>
</div>