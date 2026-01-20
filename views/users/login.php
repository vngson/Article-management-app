
    <div class="login">
        <div style="text-align: center; margin-bottom: 20px;">
            <i class="fas fa-lock" style="font-size: 4rem; color: #6366f1; margin-bottom: 10px;"></i>
            <h2 class="login__title" style="margin-top: 10px;">Welcome Back</h2>
            <p style="color: #6b7280; font-size: 1.4rem; margin: 5px 0 0 0;">Sign in to your account</p>
        </div>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        <form action="index.php?action=login" method="post">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-key"></i> Password</label>
                <input type="password" class="form-control" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-search" style="width: 100%; margin-top: 10px;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        <hr style="width: 100%; border-color: #e5e7eb; margin: 20px 0;">
        <p style="font-size: 1.4rem; color: #6b7280; text-align: center;">
            Don't have an account? <a href="index.php?action=login" style="color: #6366f1; font-weight: 600; text-decoration: none;">Contact administrator</a>
        </p>
    </div>

