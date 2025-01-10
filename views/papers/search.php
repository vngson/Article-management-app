<div class="search-top">
    <h2 class="search_title">Search Papers</h2>
    <form id="searchForm" action="index.php" method="GET">
        <input type="hidden" name="action" value="search_papers">
        <div class="searchForm_input">
            <div class="form-group">
                <label for="keyword">Keyword:</label>
                <input type="text" class="form-control" id="keyword" name="keyword" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= isset($_GET['author']) ? htmlspecialchars($_GET['author']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="conference">Conference:</label>
                <input type="text" class="form-control" id="conference" name="conference" value="<?= isset($_GET['conference']) ? htmlspecialchars($_GET['conference']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" class="form-control" id="year" name="year" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="topic">Topic:</label>
                <input type="text" class="form-control" id="topic" name="topic" value="<?= isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : '' ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-search">Search</button>
    </form>
    <div id="searchResults"></div>
    <div id="pagination"></div>
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
                        $('#searchResults').append('<div><a href="index.php?action=view_paper&id=' + paper.paper_id + '">' + paper.title + '</a></div>');
                    });

                    for (let i = 1; i <= response.total_pages; i++) {
                        $('#pagination').append('<a href="#" onclick="performSearch(' + i + '); return false;">' + i + '</a> ');
                    }
                } else {
                    $('#searchResults').html('<p>No results found.</p>');
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