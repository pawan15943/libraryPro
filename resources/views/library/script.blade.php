<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     // Handle state and city dropdowns
     $('#stateid').on('change', function(event){
            event.preventDefault();
            var state_id = $(this).val();
            console.log(state_id);
            if(state_id){
                $.ajax({
                    url: '{{ route('cityGetStateWise') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "state_id": state_id,
                    },
                    dataType: 'json',
                    success: function(html) {
                        if(html){
                            $("#cityid").empty();
                            $("#cityid").append('<option value="">Select City</option>');
                            $.each(html, function(key, value){
                                var selected = (key == "{{ old('city_id', isset($student) ? $student->city_id : '') }}") ? 'selected' : '';
                                $("#cityid").append('<option value="'+key+'" '+selected+'>'+value+'</option>');
                            });
                        }else{
                            $("#cityid").append('<option value="">Select City</option>');
                        }
                    }
                });
            } else {
                $("#cityid").empty();
                $("#cityid").append('<option value="">Select City</option>');
            }
        });
</script>