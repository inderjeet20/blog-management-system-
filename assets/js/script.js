$(document).ready(function() {
    function loadBlogs() {
        var search = $('#search').val();
        var category = $('#categoryFilter').val();
        var date_order = $('#dateFilter').val();

        $.ajax({
            url: 'fetch_blogs.php',
            type: 'GET',
            data: {
                search: search,
                category: category,
                date_order: date_order
            },
            success: function(response) {
                $('#blogsContainer').html(response);
            }
        });
    }

    // Initial load
    loadBlogs();

    // Trigger on change or input
    $('#search').on('keyup', function() {
        loadBlogs();
    });

    $('#categoryFilter, #dateFilter').on('change', function() {
        loadBlogs();
    });
});