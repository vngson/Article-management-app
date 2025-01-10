<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Paper Hub</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="index.php"><img src="/assets/images/logo.png" alt="logo_web" class="logo_web"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=search_papers">Search</a>
                </li>
                <?php
                if (isset($_SESSION['user_id'])) {
                    // Nếu đã đăng nhập
                    echo '<li class="nav-item-end">
                            <a class="nav-link" href="index.php?action=author_profile">Profile</a>
                            <a class="nav-link" href="index.php?action=logout">Logout</a>
                          </li>';
                } else {
                    // Nếu chưa đăng nhập
                    echo '<li class="nav-item-end">
                            <a class="nav-link" href="index.php?action=login">Login</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
