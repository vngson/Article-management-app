<?php
session_start();

require_once 'controllers/UserController.php';
require_once 'controllers/PaperController.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;
    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;
    case 'list_papers':
        $controller = new PaperController();
        $controller->list();
        break;
    case 'search_papers':
        $controller = new PaperController();
        $controller->search();
        break;
    case 'add_paper':
        $controller = new PaperController();
        $controller->addPaper();
        break;
    case 'author_profile':
        $controller = new UserController();
        $controller->authorProfileShow();
        break;
    case 'update_profile':
        $controller = new UserController();
        $controller->handleUpdateProfile();
        break;
    case 'add_paper':
        $controller = new PaperController();
        $controller->addPaper();
        break;
    case 'view_paper':
        $controller = new PaperController();
        $controller->viewPaperDetail($_GET['paper_id'] ?? null); // Assuming paper_id is passed in URL
        break;
    case 'add_me_to_paper':
        $controller = new PaperController();
        $controller->addMeToPaper($_GET['paper_id'] ?? null); // Assuming paper_id is passed in URL
        break;
    default:
        $controller = new PaperController();
        $controller->home();
        break;
}
?>
