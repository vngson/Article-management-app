<div class="row">
    <div class="col-md-12 home">
        <h2 class="home__title">List of latest papers</h2>
        <?php foreach($papersByTopic as $topic => $papers): ?>
            <h3 class="home__paper_topic"><?php echo htmlspecialchars($topic); ?></h3>
            <div class="home__paper_list">
                <?php foreach($papers as $paper): ?>
                    <a href="index.php?action=view_paper&paper_id=<?php echo $paper['paper_id']; ?>" class="home__paper">
                        <h4 class="home__paper--title">
                            <?php echo htmlspecialchars($paper['title']); ?>
                        </h4>
                        <span class="home__paper--abstract">
                            <?php echo htmlspecialchars($paper['abstract']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
