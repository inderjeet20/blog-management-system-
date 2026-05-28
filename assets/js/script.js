$(document).ready(function() {
    var searchTimer = null;

    function loadBlogs() {
        var search = $('#search').val();
        var category = $('#categoryFilter').val();
        var date_order = $('#dateFilter').val();
        var from_date = $('#fromDate').val();
        var to_date = $('#toDate').val();

        $.ajax({
            url: 'fetch_blogs.php',
            type: 'GET',
            data: {
                search: search,
                category: category,
                date_order: date_order,
                from_date: from_date,
                to_date: to_date
            },
            success: function(response) {
                $('#blogsContainer').html(response);
            }
        });
    }

    function debounceLoad() {
        if (searchTimer) {
            clearTimeout(searchTimer);
        }
        searchTimer = setTimeout(function() {
            loadBlogs();
        }, 300);
    }

    // Initial load
    loadBlogs();

    // Trigger on change or input
    $('#search').on('input', function() {
        debounceLoad();
    });

    $('#categoryFilter, #dateFilter, #fromDate, #toDate').on('change', function() {
        loadBlogs();
    });

    $('#clearFilters').on('click', function() {
        $('#search').val('');
        $('#categoryFilter').val('');
        $('#dateFilter').val('DESC');
        $('#fromDate').val('');
        $('#toDate').val('');
        loadBlogs();
    });
});