<?php
require_once 'config/config.inc.php';

class User {
    private $db;

    public function __construct() {
        global $db; // Sử dụng biến toàn cục $db từ config.php
        $this->db = $db;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM USERS WHERE email = :email AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    }

    public function getAuthorInfoByUserId($user_id) {
        // Câu truy vấn để lấy thông tin cơ bản của tác giả từ bảng AUTHORS và thông tin chi tiết của các bài báo mà tác giả tham gia
        $query = "
            SELECT a.user_id, a.full_name, a.website, a.profile_json_text, a.image_path,
                   GROUP_CONCAT(DISTINCT CONCAT_WS(':', p.paper_id, p.title, p.abstract, pa.date_added) ORDER BY pa.date_added SEPARATOR ';') AS papers_info,
                   MIN(pa.date_added) AS first_participation_date
            FROM AUTHORS a
            LEFT JOIN PARTICIPATION pa ON a.user_id = pa.author_id
            LEFT JOIN PAPERS p ON pa.paper_id = p.paper_id
            WHERE a.user_id = :user_id
            GROUP BY a.user_id
        ";
    
        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        $authorInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Xử lý profile_json_text nếu có
        if (!empty($authorInfo['profile_json_text'])) {
            $profileData = json_decode($authorInfo['profile_json_text'], true);
    
            // Xử lý bio
            if (isset($profileData['bio'])) {
                if (is_array($profileData['bio'])) {
                    $authorInfo['bio'] = implode(', ', $profileData['bio']);
                } else {
                    $authorInfo['bio'] = $profileData['bio'];
                }
            } else {
                $authorInfo['bio'] = '';
            }
    
            // Xử lý interests
            if (isset($profileData['interests'])) {
                if (is_array($profileData['interests'])) {
                    $authorInfo['interests'] = implode(', ', $profileData['interests']);
                } else {
                    $authorInfo['interests'] = $profileData['interests'];
                }
            } else {
                $authorInfo['interests'] = '';
            }
        } else {
            $authorInfo['bio'] = '';
            $authorInfo['interests'] = '';
        }
    
        // Xử lý danh sách các bài báo
        $papers = [];
        if (!empty($authorInfo['papers_info'])) {
            $papersInfo = explode(';', $authorInfo['papers_info']);
            foreach ($papersInfo as $paperInfo) {
                list($paper_id, $title, $abstract, $date_added) = explode(':', $paperInfo);
                $papers[] = ['paper_id' => $paper_id, 'title' => $title, 'abstract' => $abstract, 'date_added' => $date_added];
            }
    
            $authorInfo['papers'] = $papers;
        } else {
            $authorInfo['papers'] = []; // Không có bài báo nào
        }
    
        // Tính toán số năm, tháng và ngày làm việc từ first_participation_date đến ngày hiện tại
        if (!empty($authorInfo['first_participation_date'])) {
            $firstDate = new DateTime($authorInfo['first_participation_date']);
            $now = new DateTime();
            $interval = $firstDate->diff($now);
    
            $years = $interval->y;
            $months = $interval->m;
            $days = $interval->d;
    
            // Định dạng chuỗi kết quả
            $authorInfo['work_experiences'] = '';
            if ($years > 0) {
                $authorInfo['work_experiences'] .= "$years năm ";
            }
            if ($months > 0) {
                $authorInfo['work_experiences'] .= "$months tháng ";
            }
            if ($days > 0) {
                $authorInfo['work_experiences'] .= "$days ngày";
            }
        } else {
            $authorInfo['work_experiences'] = 'Chưa có thông tin'; // Nếu không có ngày tham gia
        }
    
        // Lấy danh sách các topic_id mà tác giả đã viết bài
        $queryTopics = "
            SELECT DISTINCT p.topic_id
            FROM PAPERS p
            JOIN PARTICIPATION pa ON p.paper_id = pa.paper_id
            WHERE pa.author_id = :user_id
        ";
        $stmtTopics = $this->db->prepare($queryTopics);
        $stmtTopics->execute([':user_id' => $user_id]);
        $topicIds = $stmtTopics->fetchAll(PDO::FETCH_COLUMN);
    
        // Lấy tên các chủ đề từ bảng TOPICS
        if (!empty($topicIds)) {
            $queryTopicNames = "
                SELECT topic_name
                FROM TOPICS
                WHERE topic_id IN (" . implode(',', $topicIds) . ")
            ";
            $stmtTopicNames = $this->db->prepare($queryTopicNames);
            $stmtTopicNames->execute();
            $topicNames = $stmtTopicNames->fetchAll(PDO::FETCH_COLUMN);
    
            $authorInfo['education'] = implode(', ', $topicNames); // Danh sách các chủ đề nghiên cứu
        } else {
            $authorInfo['education'] = 'Chưa có thông tin'; // Nếu không có chủ đề nghiên cứu
        }
    
        return $authorInfo;
    }
    
    
    public function updateAuthorProfile($user_id, $full_name, $website, $bio, $interests, $image_path) {
        $profileData = ['bio' => $bio, 'interests' => $interests];
        $profile_json_text = json_encode($profileData);

        $query = "
            UPDATE AUTHORS
            SET full_name = :full_name, website = :website, profile_json_text = :profile_json_text, image_path = :image_path
            WHERE user_id = :user_id
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':full_name' => $full_name,
            ':website' => $website,
            ':profile_json_text' => $profile_json_text,
            ':image_path' => $image_path,
            ':user_id' => $user_id
        ]);
    }
}
?>
