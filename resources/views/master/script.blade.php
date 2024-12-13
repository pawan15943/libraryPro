
    

<script>
   $(document).ready(function() {
    // Hide form-fields by default
    $('.form-fields').hide();

    // Toggle form-fields and icon on button click
    $('.toggle-button').click(function() {
        $(this).closest('.master-box').find('.form-fields').toggle();
        $(this).toggleClass('fa-plus-circle fa-minus-circle');
    });

    // Initialize Flatpickr for start_time and end_time
    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", // Use 24-hour format
        time_24hr: true,
        onChange: function() {
            calculateSlotHours();
        }
    });

    flatpickr("#end_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", // Use 24-hour format
        time_24hr: true,
        onChange: function() {
            calculateSlotHours();
        }
    });

    function calculateSlotHours() {
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();

        if (startTime && endTime) {
            var start = new Date("1970-01-01T" + startTime + ":00Z");
            var end = new Date("1970-01-01T" + endTime + ":00Z");
            var diffInMinutes = (end - start) / 1000 / 60;

            if (diffInMinutes < 0) {
                diffInMinutes += 24 * 60; // Adjust for crossing midnight
            }

            var hours = Math.floor(diffInMinutes / 60);

            $('#slot_hours').val(hours); // Display only the hours
        }
    }
});

   

</script>

<script>
    $(document).ready(function() {
        $(document.body).off('submit', '#planForm, #planTypeForm , #operating_hour , #library_seat, #planPriceForm , #extend_hour')
            .on('submit', '#planForm, #planTypeForm , #operating_hour , #library_seat, #planPriceForm , #extend_hour,#library_expense', function(event) {
            event.preventDefault(); 
            
            var formData = new FormData(this);
            var formId = $(this).attr('id');
            var url;

          
            if (formId === 'library_seat'){
                url = '{{ route('seats.store') }}';
            } else if (formId === 'extend_hour'){
                url = '{{ route('extendDay.store') }}';
            }else{
                url = '{{ route('master.store') }}';
            }

            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                
                    if (response.success) {

                        $("#success-message").text(response.message).show();
                        $("#error-message").hide();
                        $('.form-fields').hide();
                        $("#" + formId)[0].reset(); 
                        
                    } else if (response.errors) {
                        $(".is-invalid").removeClass("is-invalid");
                        $(".invalid-feedback").remove();

                        $.each(response.errors, function(key, value) {
                            var element = $("[name='" + key + "']");
                            element.addClass("is-invalid");
                            element.after('<span class="invalid-feedback" role="alert">' + value + '</span>');
                        });
                    } else {
                        $("#error-message").text(response.message).show();
                        $("#success-message").hide();
                    }

                    
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    $('#error-message').text(response.message).show();
                    $('#success-message').hide();

                
                }
            });

            return false; 
        });

        $(document.body).on('click', '.plan_edit ,.plantype_edit, .hour_edit,.seat_edit,.extend_day_edit ,.planPrice_edit,.expense_edit', function() {
            var id = $(this).data('id');
            $(this).closest('.master-box').find('.form-fields').toggle();

            var formId = $(this).attr('class');  
          
            var modeltable=$(this).data('table');
           
            $.ajax({
                url: '{{ route('master.edit') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "modeltable" :modeltable
                },

                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if(response.Plan){
                        $('input[name="id"]').val(response.Plan.id);
                
                        $('#plan_id').val(response.Plan.plan_id);

                        $('#savePlanBtn').text('Update Plan');
                    }
            
                    if(response.PlanType){
                        $('input[name="id"]').val(response.PlanType.id);
                        
                        $('#plantype_name').val(response.PlanType.day_type_id);
                        $('#start_time').val(response.PlanType.start_time);
                        $('#end_time').val(response.PlanType.end_time);
                        $('#slot_hours').val(response.PlanType.slot_hours);
                        if(response.PlanType.day_type_id == 4){
                            $('select[name="timming"]').val('Morning1');
                        } else if(response.PlanType.day_type_id == 5){
                            $('select[name="timming"]').val('Morning2');
                        } else if(response.PlanType.day_type_id == 6){
                            $('select[name="timming"]').val('Evening1');
                        } else if(response.PlanType.day_type_id == 7){ 
                            $('select[name="timming"]').val('Evening2');
                        }


                        $('#savePlanTypeBtn').text('Update Plan Type');
                    }

                    if(response.PlanPrice){
                        $('input[name="id"]').val(response.PlanPrice.id);
                
                        $('#price_plan_id').val(response.PlanPrice.plan_id);
                        $('#plan_type_id').val(response.PlanPrice.plan_type_id);
                        $('#price').val(response.PlanPrice.price);
                    }

                    if(response.Hour){
                        $('input[name="id"]').val(response.Hour.id);
                        $('#hour').val(response.Hour.hour).change();
                        $('input[name="extend_days"]').val(response.Hour.extend_days);
                    }

                    if(response.Seat){
                        $('input[name="total_seats"]').val(response.Seat);
                        
                    }
                    if(response.Expense){
                        $('input[name="id"]').val(response.Expense.id);
                
                       
                        $('input[name="name"]').val(response.Expense.name);
                    }

                
                }
            });

        });

        $(document).on('click', '.active-deactive , .delete', function(e) {
            e.preventDefault();
            
            var dataId = $(this).data('id');
            var formId = $(this).attr('class');  
           
            var url = '{{ route("activeDeactive", ":id") }}'.replace(':id', dataId);

            var dataTable=$(this).data('table');
        
            var $row = $(this).closest('tr'); // Get the closest table row to update it later
            console.log(dataTable);
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
    });

</script>

   



