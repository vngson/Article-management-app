<?php
require_once 'models/Paper.php';
require_once 'BaseController.php';

class PaperController extends BaseController {

    // Method to list latest papers
    public function list() {
        $paperModel = new Paper();
        $papers = $paperModel->getLatestPapers();
        $this->render('papers/list', ['papers' => $papers]);
    }

    // Method to show latest papers by topic
    public function home() {
        $paperModel = new Paper();
        $papersByTopic = $paperModel->getLatestPapersByTopic();
        $this->render('papers/home', ['papersByTopic' => $papersByTopic]);
    }

    // Method to handle paper search
    public function search() {
        $paperModel = new Paper();
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $author = isset($_GET['author']) ? $_GET['author'] : '';
        $conference = isset($_GET['conference']) ? $_GET['conference'] : '';
        $year = isset($_GET['year']) ? $_GET['year'] : '';
        $topic = isset($_GET['topic']) ? $_GET['topic'] : '';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    
        // Perform search based on criteria
        if ($keyword || $author || $conference || $year || $topic) {
            $result = $paperModel->searchPapers($keyword, $author, $conference, $year, $topic, $page);
        } else {
            $result = ['data' => [], 'total_pages' => 0];
        }
    
        // Return JSON response for AJAX requests, otherwise render search results view
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit; // Kết thúc để không chạy tiếp vào phần render
        }
    
        $this->render('papers/search', [
            'result' => $result,
            'keyword' => $keyword,
            'author' => $author,
            'conference' => $conference,
            'year' => $year,
            'topic' => $topic
        ]);
    }

    // Method to add a new paper
    public function addPaper() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_paper') {
            // Xử lý dữ liệu gửi từ form
            $title = $_POST['title'];
            $author_string_list = $_POST['author_string_list'];
            $abstract = $_POST['abstract'];
            $conference_id = $_POST['conference'];
            $topic_id = $_POST['topic'];
            
            // Lấy user_id từ SESSION
            session_start();
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                echo "User not logged in.";
                return;
            }
            
            // Thực hiện thêm bài báo vào cơ sở dữ liệu
            $paperModel = new Paper();
            $result = $paperModel->insertPaper($title, $abstract, $author_string_list, $conference_id, $topic_id, $user_id);

            if ($result) {
                // Thông báo thành công và điều hướng hoặc hiển thị thông báo
                header('Location: index.php?action=add_paper&success=true');
                exit;
            } else {
                // Thông báo lỗi nếu có
                echo "Failed to add paper. Please try again.";
            }
        } else {
            // Nếu không phải POST request hoặc action không phải là add_paper, hiển thị form thêm bài báo
            $allTopics = $this->getAllTopics();
            $allConferences = $this->getAllConferences();
            $this->render('papers/add_paper', ['allTopics' => $allTopics, 'allConferences' => $allConferences]);
        }
    }

    // Method to retrieve all topics
    public function getAllTopics() {
        $paperModel = new Paper();
        return $paperModel->getAllTopics();
    }

    // Method to retrieve all conferences
    public function getAllConferences() {
        $paperModel = new Paper();
        return $paperModel->getAllConferences();
    }

    public function viewPaperDetail($paper_id) {
        $paperModel = new Paper();
        $paper = $paperModel->getPaperDetail($paper_id);

        if (!$paper) {
            echo "Paper not found.";
            return;
        }

        $userModel = new User();
        $user_id = $_SESSION['user_id'] ?? null;

        $authors = $paperModel->getAuthorsByPaper($paper_id);
        $isAuthor = $paperModel->isAuthorOfPaper($paper_id, $user_id);
        $isAdmin = $userModel->isAdmin(); // Check if current user is admin

        $this->render('papers/view_paper', [
            'paper' => $paper,
            'authors' => $authors,
            'isAuthor' => $isAuthor,
            'isAdmin' => $isAdmin
        ]);
    }

    public function deleteAuthorFromPaper() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_author_from_paper') {
            // Lấy thông tin từ request
            $paper_id = $_POST['paper_id'];
            $author_id = $_POST['author_id'];
    
            // Gọi hàm xóa tác giả từ model
            $paperModel = new Paper();
            $result = $paperModel->deleteAuthorFromPaper($paper_id, $author_id);
    
            // Trả về kết quả cho client (JavaScript)
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit;
        }
    }   

    public function addMeToPaper($paper_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_me_to_paper') {
            // Lấy paper_id từ form hoặc từ query string
            $paper_id = $_POST['paper_id'] ?? $_GET['id'] ?? null;
            
            // Kiểm tra xem người dùng đã đăng nhập và có quyền là tác giả không
            if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'member') {
                echo "You do not have permission to perform this action.";
                return;
            }
    
            // Gọi model để xử lý
            $paperModel = new Paper();
            $result = $paperModel->addAuthorToPaper($paper_id, $_SESSION['user_id']);
    
            if ($result) {
                // Điều hướng hoặc thông báo thành công
                header('Location: index.php?action=view_paper&paper_id=' . $paper_id);
                exit;
            } else {
                // Thông báo lỗi nếu có
                echo "Failed to add you to the paper. Please try again.";
            }
        } else {
            // Nếu không phải POST request hoặc action không phải là add_me_to_paper, có thể hiển thị trang view_paper.php ở đây
            echo "Invalid request.";
        }
    }

}
?>
