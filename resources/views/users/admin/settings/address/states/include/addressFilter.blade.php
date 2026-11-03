<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Filter script loaded');

        // Get all filter buttons
        const filterButtons = document.querySelectorAll('.filter-option');
        const tableRows = document.querySelectorAll('table tbody tr');

        console.log('Found filter buttons:', filterButtons.length);
        console.log('Found table rows:', tableRows.length);

        // Filter function
        function filterTable(status) {
            console.log('Filtering by:', status);

            let visibleCount = 0;

            tableRows.forEach(row => {
                // Skip "no records" row
                if (row.querySelector('td[colspan]')) {
                    return;
                }

                // Get status badge
                const statusBadge = row.querySelector('.badge');

                if (!statusBadge) {
                    row.style.display = '';
                    return;
                }

                // Determine row status
                let rowStatus = 'inactive';
                if (statusBadge.classList.contains('bg-soft-success') ||
                    statusBadge.classList.contains('text-success')) {
                    rowStatus = 'active';
                }

                // Show/hide row
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            console.log('Visible rows:', visibleCount);

            // Update button states
            filterButtons.forEach(btn => {
                if (btn.getAttribute('data-filter') === status) {
                    btn.classList.add('active', 'fw-bold');
                    btn.style.backgroundColor = '#f0f4ff';
                } else {
                    btn.classList.remove('active', 'fw-bold');
                    btn.style.backgroundColor = '';
                }
            });
        }

        // Add click event to each filter button
        filterButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Filter clicked');

                const filterValue = this.getAttribute('data-filter');
                console.log('Filter value:', filterValue);

                filterTable(filterValue);
            });
        });

        // Set "All" as default
        console.log('Setting default filter to: all');
        filterTable('all');
    });
</script>
