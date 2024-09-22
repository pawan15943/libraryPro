
<script>
    $(document).on('click', '.active-deactive', function(e) {
        e.preventDefault();
        
        var dataId = $(this).data('id');
       
        var url = '{{ route("activeDeactive", ":id") }}'.replace(':id', dataId);

        var dataTable=$(this).data('table');
       
        var $row = $(this).closest('tr'); // Get the closest table row to update it later
      
        // Show a confirmation dialog
        if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    dataTable: dataTable
                },
                success: function(response) {
                    if (response.status === 'success') {
                       
                        // Update the button text with an icon using .html() instead of .text()
                        $row.find('.active-deactive').html(response.data_status === 'activated' 
                            ? '<i class="fas fa-ban"></i>' 
                            : '<i class="fas fa-check"></i>');

                        // Optionally, update the status in another column
                        $row.find('.status-column').text(response.data_status === 'activated' ? 'Active' : 'Inactive');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

    });
</script>
