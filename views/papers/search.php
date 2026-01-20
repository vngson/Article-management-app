<div class="search-top">
    <h2 class="search_title"><i class="fas fa-search"></i> Search Papers</h2>
    <form id="searchForm" action="index.php" method="GET">
        <input type="hidden" name="action" value="search_papers">
        <div class="searchForm_input">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="keyword"><i class="fas fa-file-alt"></i> Keyword:</label>
                <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search by title or content..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="author"><i class="fas fa-user"></i> Author:</label>
                <input type="text" class="form-control" id="author" name="author" placeholder="Author name..." value="<?= isset($_GET['author']) ? htmlspecialchars($_GET['author']) : '' ?>">
            </div>
        </div>
        <div class="searchForm_input">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="conference"><i class="fas fa-building"></i> Conference:</label>
                <input type="text" class="form-control" id="conference" name="conference" placeholder="Conference name..." value="<?= isset($_GET['conference']) ? htmlspecialchars($_GET['conference']) : '' ?>">
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="year"><i class="fas fa-calendar"></i> Year:</label>
                <input type="text" class="form-control" id="year" name="year" placeholder="Year..." value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">
            </div>
        </div>
        <div class="searchForm_input">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="topic"><i class="fas fa-tag"></i> Topic:</label>
                <input type="text" class="form-control" id="topic" name="topic" placeholder="Topic..." value="<?= isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : '' ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-search" style="width: 200px;">
            <i class="fas fa-search"></i> Search
        </button>
    </form>
    <div id="searchResults"></div>
    <div id="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px; flex-wrap: wrap;"></div>
</div>

<script>
$(document).ready(function() {
    $('#searchForm').submit(function(event) {
        event.preventDefault();
        performSearch(1);
    });

    function performSearch(page) {
        $.ajax({
            url: 'index.php',
            type: 'GET',
            data: $('#searchForm').serialize() + '&page=' + page + '&ajax=true',
            success: function(response) {
                if (response.data.length > 0) {
                    $('#searchResults').html('');
                    $('#pagination').html('');

                    response.data.forEach(function(paper) {
                        $('#searchResults').append(
                            '<div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease;">' +
                            '<a href="index.php?action=view_paper&id=' + paper.paper_id + '" style="color: #6366f1; text-decoration: none; font-weight: 600; font-size: 1.5rem;">' + 
                            '<i class="fas fa-file-pdf"></i> ' + paper.title + '</a>' +
                            '<p style="color: #6b7280; margin: 10px 0 0 0; font-size: 1.3rem;">by ' + (paper.author || 'Unknown') + '</p>' +
                            '</div>'
                        );
                    });

                    for (let i = 1; i <= response.total_pages; i++) {
                        let btnClass = i == page ? 'style="background: #6366f1; color: white;"' : 'style="background: white; border: 2px solid #6366f1; color: #6366f1;"';
                        $('#pagination').append(
                            '<button type="button" ' + btnClass + ' onclick="performSearch(' + i + '); return false;" ' +
                            'style="padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; border: 2px solid #6366f1;">' + i + '</button>'
                        );
                    }
                } else {
                    $('#searchResults').html('<div style="background: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"><i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i><p style="color: #6b7280; font-size: 1.5rem;">No results found. Try different keywords.</p></div>');
                    $('#pagination').html('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
});
</script>
