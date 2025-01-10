
    <div class="login">
        <h2 class="login__title">Login</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="index.php?action=login" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-search">Login</button>
        </form>
    </div>

