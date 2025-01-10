<div class="view_paper">
<h2 class="view_paper__title">Paper Detail</h2>
    <h3>Title: <?= htmlspecialchars($paper['title']) ?></h3>
    <p><strong>Abstract:</strong> <?= htmlspecialchars($paper['abstract']) ?></p>
    <p><strong>Conference:</strong> <?= htmlspecialchars($paper['conference_name']) ?></p>
    <p><strong>Topic:</strong> <?= htmlspecialchars($paper['topic_name']) ?></p>

    <!-- Danh sách tác giả -->
    <h3>Authors</h3>
    <ul class="authors-list">
        <?php foreach ($authors as $author): ?>
            <li class="author-item">
                <?= htmlspecialchars($author['full_name']) ?> (<?= htmlspecialchars($author['role']) ?>)
                <?php if ($isAdmin): ?>
                    <span class="delete-author" data-paper-id="<?= $paper['paper_id'] ?>" data-author-id="<?= $author['user_id'] ?>" title="Delete author">X</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Form thêm mình vào bài báo (nếu là member) -->
    <?php if ($_SESSION['user_type'] === 'member'): ?>
        <form action="index.php?action=add_me_to_paper" method="post">
            <input type="hidden" name="paper_id" value="<?= $paper['paper_id'] ?>">
            <input type="submit" value="Add Me to Paper">
        </form>
    <?php endif; ?>

    <!-- Đưa vào các tài nguyên JavaScript cần thiết -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Xử lý sự kiện click để xóa tác giả
        $(document).on('click', '.delete-author', function() {
            if (confirm('Are you sure you want to delete this author?')) {
                var paperId = $(this).data('paper-id');
                var authorName = $(this).data('author-name');

                $.ajax({
                    url: 'index.php?action=delete_author_from_paper',
                    method: 'POST',
                    data: {
                        paper_id: paperId,
                        author_name: authorName
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