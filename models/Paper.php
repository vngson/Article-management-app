<?php
require_once 'config/config.inc.php';

class Paper {
    private $db;

    public function __construct() {
        global $db; // Sử dụng biến toàn cục $db từ config.php
        $this->db = $db;
    }

    public function getLatestPapers() {
        $query = "SELECT * FROM PAPERS ORDER BY paper_id DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestPapersByTopic() {
        $query = "
            SELECT p.*, t.topic_name
            FROM PAPERS p
            JOIN TOPICS t ON p.topic_id = t.topic_id
            JOIN PARTICIPATION pa ON p.paper_id = pa.paper_id
            WHERE YEAR(pa.date_added) = YEAR(CURDATE())
            ORDER BY pa.date_added DESC, p.paper_id DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $papers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $papers[$row['topic_name']][] = $row;
        }

        // Lấy top 5 bài báo mỗi chủ đề
        foreach ($papers as $topic => $paperList) {
            $papers[$topic] = array_slice($paperList, 0, 5);
        }

        return $papers;
    }

    public function searchPapers($keyword, $author, $conference, $year, $topic, $page) {
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $query = "
            SELECT SQL_CALC_FOUND_ROWS P.paper_id, P.title, P.abstract, C.name AS conference_name, T.topic_name AS topic_name, GROUP_CONCAT(A.full_name SEPARATOR ', ') AS authors
            FROM PAPERS P
            LEFT JOIN CONFERENCES C ON P.conference_id = C.conference_id
            LEFT JOIN TOPICS T ON P.topic_id = T.topic_id
            LEFT JOIN PARTICIPATION PA ON P.paper_id = PA.paper_id
            LEFT JOIN AUTHORS A ON PA.author_id = A.user_id
            WHERE (P.title LIKE :keyword OR P.abstract LIKE :keyword)
            AND (A.full_name LIKE :author)
            AND (C.name LIKE :conference)
            AND (YEAR(PA.date_added) = :year)
            AND (T.topic_name LIKE :topic)
            GROUP BY P.paper_id
            LIMIT :offset, :limit
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':author', '%' . $author . '%', PDO::PARAM_STR);
        $stmt->bindValue(':conference', '%' . $conference . '%', PDO::PARAM_STR);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':topic', '%' . $topic . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $stmt = $this->db->query("SELECT FOUND_ROWS()");
        $total_records = $stmt->fetchColumn();
        $total_pages = ceil($total_records / $limit);
    
        return ['data' => $data, 'total_pages' => $total_pages];
    }

    public function insertPaper($title, $abstract, $author_string_list, $conference_id, $topic_id, $user_id) {
        $query = "
            INSERT INTO PAPERS (title, author_string_list, abstract, conference_id, topic_id, user_id)
            VALUES (:title, :author_string_list, :abstract, :conference_id, :topic_id, :user_id)
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':title' => $title,
            ':abstract' => $abstract,
            ':author_string_list' => $author_string_list,
            ':conference_id' => $conference_id,
            ':topic_id' => $topic_id,
            ':user_id' => $user_id
        ]);
    }

    // Hàm để lấy chi tiết bài báo bằng paper_id
    public function getPaperDetail($paper_id) {
        $query = "
            SELECT 
                P.paper_id, 
                P.title, 
                P.abstract, 
                C.name AS conference_name, 
                T.topic_name AS topic_name, 
                P.author_string_list AS author_list,
                GROUP_CONCAT(CONCAT(A.full_name, ' (', PA.role, ')') SEPARATOR ', ') AS authors
            FROM PAPERS P
            INNER JOIN CONFERENCES C ON P.conference_id = C.conference_id
            INNER JOIN TOPICS T ON P.topic_id = T.topic_id
            INNER JOIN PARTICIPATION PA ON P.paper_id = PA.paper_id
            INNER JOIN AUTHORS A ON PA.author_id = A.user_id
            WHERE P.paper_id = :paper_id
            GROUP BY P.paper_id, P.title, P.abstract, C.name, T.topic_name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':paper_id' => $paper_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hàm để lấy danh sách tác giả của bài báo
    public function getAuthorsByPaper($paper_id) {
        $query = "
            SELECT A.user_id, A.full_name, P.role
            FROM AUTHORS A
            INNER JOIN PARTICIPATION P ON A.user_id = P.author_id
            WHERE P.paper_id = :paper_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':paper_id' => $paper_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm để kiểm tra xem user có phải là tác giả của bài báo không
    public function isAuthorOfPaper($paper_id, $user_id) {
        $query = "
            SELECT COUNT(*)
            FROM AUTHORS A
            INNER JOIN PAPERS PA ON A.user_id = PA.user_id
            WHERE PA.paper_id = :paper_id AND A.user_id = :user_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':paper_id' => $paper_id,
            ':user_id' => $user_id
        ]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function addAuthorToPaper($paper_id, $user_id) {
        $userModel = new User();
        // Lấy thông tin bài báo từ paper_id
        $paperInfo = $this->getPaperDetail($paper_id);
    
        if (!$paperInfo) {
            return false; // Không tìm thấy bài báo
        }
    
        // Kiểm tra xem user_id đã tồn tại trong danh sách tác giả của bài báo chưa
        $author_string_list = $paperInfo['author_list'];
        $authors = explode(',', $author_string_list);
    
        // Lấy thông tin người dùng từ user_id
        $user = $userModel->getAuthorInfoByUserId($user_id);
    
        // Kiểm tra xem tên người dùng đã có trong danh sách tác giả chưa
        if (!in_array($user['full_name'], $authors)) {
            // Thêm tên người dùng mới vào danh sách tác giả
            $authors[] = $user['full_name'];
    
            // Cập nhật lại author_string_list trong bảng PAPERS
            $updated_author_string_list = implode(', ', $authors);
    
            $query = "
                UPDATE PAPERS
                SET author_string_list = :author_string_list
                WHERE paper_id = :paper_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':author_string_list' => $updated_author_string_list,
                ':paper_id' => $paper_id
            ]);
    
            // Thêm thông tin tác giả vào bảng PARTICIPATION
            $queryParticipation = "
                INSERT INTO PARTICIPATION (author_id, paper_id, role, date_added, status)
                VALUES (:author_id, :paper_id, :role, NOW(), 'active')
            ";
            $stmtParticipation = $this->db->prepare($queryParticipation);
            $stmtParticipation->execute([
                ':author_id' => $user_id,
                ':paper_id' => $paper_id,
                ':role' => "member"
            ]);
    
            return true; // Trả về true để cho rằng đã thêm thành công
        }
    
        // Trả về true nếu tên tác giả đã tồn tại (mặc dù không có thay đổi)
        return true;
    }
    

    public function deleteAuthorFromPaper($paper_id, $author_id) {
        $userModel = new User();
        // Lấy thông tin người dùng từ user_id
        $author = $userModel->getAuthorInfoByUserId($author_id);
        $query = "
            DELETE FROM PARTICIPATION
            WHERE paper_id = :paper_id AND author_id = :author_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':paper_id' => $paper_id,
            ':author_id' => $author_id
        ]);
    
        // Kiểm tra số dòng bị ảnh hưởng để xác nhận xóa thành công
        if ($stmt->rowCount() > 0) {
            // Cập nhật lại author_string_list trong bảng PAPERS
            $paperInfo = $this->getPaperDetail($paper_id);
            $authors = explode(',', $paperInfo['author_list']);
    
            // Loại bỏ tên tác giả từ danh sách
            foreach ($authors as $_author) {
                if ($_author == $author['full_name']) {
                    unset($authors[$_author]);
                    break; // Dừng khi đã tìm thấy và loại bỏ tên tác giả
                }
            }
    
            // Cập nhật lại author_string_list mới
            $updated_author_string_list = implode(', ', $authors);
    
            $queryUpdatePaper = "
                UPDATE PAPERS
                SET author_string_list = :author_string_list
                WHERE paper_id = :paper_id
            ";
            $stmtUpdatePaper = $this->db->prepare($queryUpdatePaper);
            $stmtUpdatePaper->execute([
                ':author_string_list' => $updated_author_string_list,
                ':paper_id' => $paper_id
            ]);
    
            return true;
        }
    
        return false; // Trả về false nếu không có dòng nào bị ảnh hưởng (không xóa được)
    }
    // Lấy danh sách tất cả các topic từ bảng topics
    public function getAllTopics() {
        try {
            $query = "SELECT topic_id, topic_name FROM topics ORDER BY topic_name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Get all topics failed: " . $e->getMessage();
            return [];
        }
    }

    // Lấy danh sách tất cả các conference từ bảng conferences
    public function getAllConferences() {
        try {
            $query = "SELECT conference_id, name FROM conferences ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Get all conferences failed: " . $e->getMessage();
            return [];
        }
    }
}
?>
