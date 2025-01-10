<!-- add_paper.php -->
<?php
$paperModel = new PaperController();
$topics = $paperModel->getAllTopics();
$conferences = $paperModel->getAllConferences();
?>
<div class="add_paper">
    <h2 class="add_paper__title">Add New Paper</h2>
    <form action="index.php?action=add_paper" method="post" class="add_paper_form">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="author_string_list">Author(s):</label><br>
        <input type="text" id="author_string_list" name="author_string_list" required><br><br>

        <label for="abstract">Abstract:</label><br>
        <textarea id="abstract" name="abstract" rows="4" cols="50" required></textarea><br><br>

        <label for="conference">Conference:</label><br>
        <select id="conference" name="conference" required>
            <?php foreach ($allConferences as $conference): ?>
                <option value="<?= $conference['conference_id'] ?>"><?= htmlspecialchars($conference['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="topic">Topic:</label><br>
        <select id="topic" name="topic" required>
            <?php foreach ($allTopics as $topic): ?>
                <option value="<?= $topic['topic_id'] ?>"><?= htmlspecialchars($topic['topic_name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" name="submit" value="Add Paper" class="btn btn-primary btn-add-paper">
    </form>
</div>

