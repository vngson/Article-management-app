<div class="view_paper">
    <h2 class="view_paper__title"><i class="fas fa-file-alt"></i> Paper Detail</h2>
    
    <div style="background: linear-gradient(135deg, #6366f1, #7c3aed); color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
        <h3 style="font-size: 2rem; margin-bottom: 10px; color: white;"><?= htmlspecialchars($paper['title']) ?></h3>
        <p style="font-size: 1.4rem; opacity: 0.9; margin: 0;">
            <i class="fas fa-calendar"></i> Conference: <strong><?= htmlspecialchars($paper['conference_name']) ?></strong> | 
            <i class="fas fa-tag"></i> Topic: <strong><?= htmlspecialchars($paper['topic_name']) ?></strong>
        </p>
    </div>

    <div style="background: #f3f4f6; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
        <h4 style="color: #6366f1; font-weight: 700; margin-bottom: 10px;"><i class="fas fa-quote-left"></i> Abstract</h4>
        <p style="font-size: 1.5rem; line-height: 1.8; color: #374151; margin: 0;">
            <?= htmlspecialchars($paper['abstract']) ?>
        </p>
    </div>

    <!-- Danh sách tác giả -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #1f2937; font-weight: 700; margin-bottom: 15px; border-bottom: 2px solid #6366f1; padding-bottom: 10px;">
            <i class="fas fa-users"></i> Authors
        </h3>
        <ul class="authors-list" style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($authors as $author): ?>
                <li class="author-item" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e5e7eb; font-size: 1.4rem;">
                    <span>
                        <i class="fas fa-user-circle"></i> 
                        <strong><?= htmlspecialchars($author['full_name']) ?></strong>
                        <span style="color: #6b7280; font-size: 1.3rem;">(<?= htmlspecialchars($author['role']) ?>)</span>
                    </span>
                    <?php if ($isAdmin): ?>
                        <button class="delete-author" data-paper-id="<?= $paper['paper_id'] ?>" data-author-id="<?= $author['user_id'] ?>" 
                            style="background: #ef4444; color: white; border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Form thêm mình vào bài báo (nếu là member) -->
    <?php if ($_SESSION['user_type'] === 'member'): ?>
        <form action="index.php?action=add_me_to_paper" method="post">
            <input type="hidden" name="paper_id" value="<?= $paper['paper_id'] ?>">
            <button type="submit" class="btn btn-primary btn-search" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Add Me to Paper
            </button>
        </form>
    <?php endif; ?>

    <!-- Đưa vào các tài nguyên JavaScript cần thiết -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Xử lý sự kiện click để xóa tác giả
        $(document).on('click', '.delete-author', function() {
            if (confirm('Are you sure you want to delete this author?')) {
                var paperId = $(this).data('paper-id');
                var authorId = $(this).data('author-id');

                $.ajax({
                    url: 'index.php?action=delete_author_from_paper',
                    method: 'POST',
                    data: {
                        paper_id: paperId,
                        author_id: authorId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Author deleted successfully!');
                            location.reload(); // Reload trang sau khi xóa thành công
                        } else {
                            alert('Failed to delete author. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Failed to delete author. Please try again.');
                    }
                });
            }
        });
    </script>
    </div>