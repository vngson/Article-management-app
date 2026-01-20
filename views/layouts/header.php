<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Paper Hub</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="index.php" style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-book" style="font-size: 2.5rem; color: white;"></i>
            <span style="font-size: 2rem; font-weight: 700; color: white;">Paper Hub</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=search_papers"><i class="fas fa-search"></i> Search</a>
                </li>
                <?php
                if (isset($_SESSION['user_id'])) {
                    // Nếu đã đăng nhập
                    echo '<li class="nav-item">
                            <a class="nav-link" href="index.php?action=add_paper"><i class="fas fa-plus"></i> Add Paper</a>
                          </li>';
                    echo '<li class="nav-item">
                            <a class="nav-link" href="index.php?action=author_profile"><i class="fas fa-user-circle"></i> Profile</a>
                          </li>';
                    echo '<li class="nav-item">
                            <a class="nav-link" href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                          </li>';
                } else {
                    // Nếu chưa đăng nhập
                    echo '<li class="nav-item">
                            <a class="nav-link" href="index.php?action=login"><i class="fas fa-sign-in-alt"></i> Login</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
