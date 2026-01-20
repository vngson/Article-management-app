<!-- add_paper.php -->
<?php
$paperModel = new PaperController();
$topics = $paperModel->getAllTopics();
$conferences = $paperModel->getAllConferences();
?>
<div class="add_paper">
    <h2 class="add_paper__title"><i class="fas fa-plus-circle"></i> Add New Paper</h2>
    <form action="index.php?action=add_paper" method="post" class="add_paper_form">
        <div class="form-group">
            <label for="title"><i class="fas fa-heading"></i> Paper Title *</label>
            <input type="text" id="title" name="title" placeholder="Enter the paper title..." required>
        </div>

        <div class="form-group">
            <label for="author_string_list"><i class="fas fa-users"></i> Author(s) *</label>
            <input type="text" id="author_string_list" name="author_string_list" placeholder="Author names separated by commas..." required>
            <small style="color: #6b7280; font-size: 1.2rem; margin-top: 5px; display: block;">e.g., John Doe, Jane Smith</small>
        </div>

        <div class="form-group">
            <label for="abstract"><i class="fas fa-quote-left"></i> Abstract *</label>
            <textarea id="abstract" name="abstract" placeholder="Enter the paper abstract..." required></textarea>
        </div>

        <div class="form-group">
            <label for="conference"><i class="fas fa-building"></i> Conference *</label>
            <select id="conference" name="conference" required>
                <option value="">-- Select Conference --</option>
                <?php foreach ($allConferences as $conference): ?>
                    <option value="<?= $conference['conference_id'] ?>"><?= htmlspecialchars($conference['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="topic"><i class="fas fa-tag"></i> Topic *</label>
            <select id="topic" name="topic" required>
                <option value="">-- Select Topic --</option>
                <?php foreach ($allTopics as $topic): ?>
                    <option value="<?= $topic['topic_id'] ?>"><?= htmlspecialchars($topic['topic_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary btn-add-paper" style="width: 100%; margin-top: 20px;">
            <i class="fas fa-save"></i> Submit Paper
        </button>
    </form>
</div>

