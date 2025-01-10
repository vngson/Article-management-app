<?php
require_once 'models/User.php';
require_once 'BaseController.php';

class UserController extends BaseController {
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->login($email, $password);

            if($user) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                header("Location: index.php?action=author_profile");
            } else {
                $this->render('users/login', ['error' => 'Invalid email or password']);
            }
        } else {
            $this->render('users/login');
        }
    }

    public function isAdmin(){
        $userModel = new User();
        return $userModel->isAdmin();
    }

    public function getAuthorInfo($user_id) {
        $userModel = new User();
    
        // Fetch author information from database based on email
        $authorInfo = $userModel->getAuthorInfoByUserID($user_id);
    
        return $authorInfo;
    }

    public function authorProfileShow() {
        return $this->render('users/author_profile');
    }

    public function updateAuthorProfile($user_id, $full_name, $website, $bio, $interests, $image_path) {
        $userModel = new User();
        return $userModel->updateAuthorProfile($user_id, $full_name, $website, $bio, $interests, $image_path);
    }

    public function handleUpdateProfile() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['user_id'];
            $full_name = $_POST['full_name'];
            $website = $_POST['website'];
            $bio = $_POST['bio'];
            $interests = $_POST['interests'];
    
            // Handle profile image upload
            $image_path = $this->uploadProfileImage('profile_image');
            if (!$image_path) {
                // Use current image path if no new image is uploaded
                $image_path = $_POST['current_image_path'];
            }
    
            // Update author profile
            $result = $this->updateAuthorProfile($user_id, $full_name, $website, $bio, $interests, $image_path);
    
            if ($result) {
                // Reload profile page after successful update
                header('Location: index.php?action=update_profile');
                exit;
            } else {
                echo "Update profile failed. Please try again.";
            }
        } else {
            // Handle non-POST requests (e.g., initial page load)
            return $this->render('users/update_profile');
        }
    }
    
    private function uploadProfileImage($image_field_name) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES[$image_field_name]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        // Check file size
        if ($_FILES[$image_field_name]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
    
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            return null;
        } else {
            // Try to upload file
            if (move_uploaded_file($_FILES[$image_field_name]["tmp_name"], $target_file)) {
                return $target_file; // Return file path if uploaded successfully
            } else {
                echo "Sorry, there was an error uploading your file.";
                return null;
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}
?>
