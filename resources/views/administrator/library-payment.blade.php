@extends('layouts.admin')


@section('content')
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<!-- Content -->

<form action="{{route('library.payment.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input name="library_id" value="{{$library_id}}" type="hidden">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="">Payment<span>*</span></label>
                        <select name="payment" class="form-select @error('payment') is-invalid @enderror">
                            <option value="">Select Payment</option>
                            <option value="new">New Payment</option>
                            <option value="renew">Renew Payment</option>
                            <option value="pending">Pending Payment</option>
                            <option value="pre">Pre Payment</option>
                          
                        </select>
                        @error('payment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="">Subscription Type <span>*</span></label>
                        <select name="month" class="form-select @error('month') is-invalid @enderror" id="month">
                            <option value="">Select</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                          
                        </select>
                        @error('month')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label for="">Plan <span>*</span></label>
                        <select name="library_type" class="form-select @error('library_type') is-invalid @enderror" id="library_type">
                         
                            <option value="">Select</option>
                            @foreach($plans as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>  
                            @endforeach
                          
                        </select>
                        @error('library_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-lg-7">
                    <div class="payment-detaile ">
            
            
                    </div>
            
                </div> --}}
                <div class="row justify-content-center mb-4">
                
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="col-lg-12">
                                <h4 class="text-center mb-4">Order Summery</h4>
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        Amount Paid
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <i class="fa fa-inr"></i> 
                                    </div>
                                    <!--GST and Discount insert in table from API call for created according -->
                                    <div class="col-lg-8">
                                        <a href="">Offer & Discounts</a>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <i class="fa fa-inr"></i> 
                                    </div>
                                    <div class="col-lg-8">
                                        % GST
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <i class="fa fa-inr"></i> 
                                    </div>
                
                
                
                                    <div class="col-lg-8">
                                        <b>Total Amount to Pay</b>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <b><i class="fa fa-inr"></i> </b>
                                    </div>
                
                                </div>
                
                            </div>
                        </div>
                    </div> 
                    <div class="col-lg-5">
                        <div class="card mt-4">
                            <h4 class="mb-3 text-center">Transaction Summery</h4>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <span>Transaction Date</span>
                                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', date('Y-m-d')) }}">
                                    @if($errors->has('transaction_date'))
                                    <span class="text-danger">{{ $errors->first('transaction_date') }}</span>
                                    @endif
            
                                </div>
            
                                <div class="col-lg-6">
                                    <span>Transaction Id</span>
                                    <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id', mt_rand(10000000, 99999999)) }}">
                                    @if($errors->has('transaction_id'))
                                    <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                                    @endif
                                </div>
            
                                <div class="col-lg-12">
                                    <span>Payment Method</span>
                                    <select name="payment_method" class="form-select">
                                        <option value="">Select Mode</option>
                                        <option value="1" {{ old('payment_method') == 'Online' ? 'selected' : '' }}>Online</option>
                                        <option value="2" {{ old('payment_method') == 'Offline' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                    @if($errors->has('payment_method'))
                                    <span class="text-danger">{{ $errors->first('payment_method') }}</span>
                                    @endif
                                </div>
                            </div>
            
                        </div>
                    </div>       
                        
                    
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-lg-12">
    
                        <input type="hidden" name="library_transaction_id" value="">
                        <button type="submit" class="btn btn-primary btn-block button" id="pay-btn"> Make Payment </button>
    
                    </div>
                </div>

               
            </div>
        </div>
    </div>

</form>
<script>
      $(document.body).on('change', '#library_type', function() {
            var library_type = $(this).val();
            var month=$('#month').val();
            $.ajax({
                url: '{{ route('get.subscription.fees')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "library_type": library_type,
                    "month": month,

                },

                dataType: 'json',
                success: function(response) {


                }
            });

        });
</script>


@endsection