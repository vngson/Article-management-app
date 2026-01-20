<div class="row">
    <div class="col-md-12 home">
        <h2 class="home__title"><i class="fas fa-book-open"></i> Latest Papers</h2>
        <?php foreach($papersByTopic as $topic => $papers): ?>
            <h3 class="home__paper_topic"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($topic); ?></h3>
            <div class="home__paper_list">
                <?php foreach($papers as $paper): ?>
                    <a href="index.php?action=view_paper&paper_id=<?php echo $paper['paper_id']; ?>" class="home__paper">
                        <h4 class="home__paper--title">
                            <?php echo htmlspecialchars($paper['title']); ?>
                        </h4>
                        <span class="home__paper--abstract">
                            <?php echo htmlspecialchars($paper['abstract']); ?>
                        </span>
                        <div style="padding: 15px; padding-top: 0; color: #6366f1; font-weight: 600; font-size: 1.3rem;">
                            Read more <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>