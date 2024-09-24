
    

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
            .on('submit', '#planForm, #planTypeForm , #operating_hour , #library_seat, #planPriceForm , #extend_hour', function(event) {
            event.preventDefault(); 
            
            var formData = new FormData(this);
            var formId = $(this).attr('id');
            var url;

          
            if (formId === 'planForm' || formId === 'operating_hour' || formId === 'planTypeForm' || formId === 'planPriceForm') {
                url = '{{ route('master.store') }}';
            }else if (formId === 'library_seat'){
                url = '{{ route('seats.store') }}';
            } else if (formId === 'extend_hour'){
                url = '{{ route('extendDay.store') }}';
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

        $(document.body).on('click', '.plan_edit ,.plantype_edit, .hour_edit,.seat_edit,.extend_day_edit ,.planPrice_edit', function() {
            var id = $(this).data('id');
            $(this).closest('.master-box').find('.form-fields').toggle();

            var formId = $(this).attr('class');  
          
            var url;
            var modeltable;

            if (formId === 'plan_edit') {
                
                modeltable='Plan';
            } else if (formId === 'plantype_edit') {
                
                modeltable='PlanType';
            }else if (formId === 'hour_edit' || formId ==='extend_day_edit') {
               
                modeltable='hour';
            }else if (formId === 'seat_edit') {
               
                modeltable='seats';
            }else if (formId === 'planPrice_edit') {
                
                modeltable='PlanPrice';
            }


        
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
                    
                    if(response.plan){
                        $('input[name="id"]').val(response.plan.id);
                
                        $('#plan_id').val(response.plan.plan_id);

                        $('#savePlanBtn').text('Update Plan');
                    }
            
                    if(response.plantype){
                        $('input[name="id"]').val(response.plantype.id);
                        
                        $('#plantype_name').val(response.plantype.name);
                        $('#start_time').val(response.plantype.start_time);
                        $('#end_time').val(response.plantype.end_time);
                        $('#slot_hours').val(response.plantype.slot_hours);
                        if(response.plantype.day_type_id == 4){
                            $('select[name="timming"]').val('Morning1');
                        } else if(response.plantype.day_type_id == 5){
                            $('select[name="timming"]').val('Morning2');
                        } else if(response.plantype.day_type_id == 6){
                            $('select[name="timming"]').val('Evening1');
                        } else if(response.plantype.day_type_id == 7){ 
                            $('select[name="timming"]').val('Evening2');
                        }


                        $('#savePlanTypeBtn').text('Update Plan Type');
                    }

                    if(response.planprice){
                        $('input[name="id"]').val(response.planprice.id);
                
                        $('#price_plan_id').val(response.planprice.plan_id);
                        $('#plan_type_id').val(response.planprice.plan_type_id);
                        $('#price').val(response.planprice.price);
                    }

                    if(response.hour){
                        $('input[name="id"]').val(response.hour.id);
                        $('#hour').val(response.hour.hour).change();
                        $('input[name="extend_days"]').val(response.hour.extend_days);
                    }

                    if(response.seats){
                        $('input[name="total_seats"]').val(response.seats);
                        
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

   



