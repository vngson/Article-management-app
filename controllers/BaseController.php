<?php
class BaseController {
    public function render($view, $data = []) {
        extract($data);
        include "views/layouts/header.php";
        include "views/$view.php";
        include "views/layouts/footer.php";
    }
}
?>
