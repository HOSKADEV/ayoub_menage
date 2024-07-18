@extends('layouts/contentNavbarLayout')

@section('title', __('Sections'))

@section('content')

<div id="loading-page">
  <h4 class="fw-bold py-3 mb-3">
    <span class="text-muted fw-light"></span> {{__('Settings')}}
  </h4>

  <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
        enctype="multipart/form-data" id="setting_form">
    <div class="row">
      <div class="col-xl-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{__('Price Maximum')}}</h5>
            <small class="text-muted float-end">{{__('Price Maximum')}}</small>
          </div>

          <div class="card-body">
            <div class="row">
              <input hidden type="text" id="setting_id" name="setting_id" value="{{ $settings->id}}">

              <div class="mb-3 col-md-6">
                <label class="form-label" for="price_max">{{__('Price Maximum')}}</label>
                <input type="text" class="form-control" id="price_max" name="price_max" placeholder="{{__('Price Maximum')}}" value="{{ $settings->price_max }}"/>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{__('Bank account number')}}</h5>
            <small class="text-muted float-end">{{__('Bank account number')}}</small>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="card-body">
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label class="form-label" for="bank_account_bankily">{{__('Bankily account number')}} </label>
                    <input type="text" class="form-control" id="bank_account_bankily" name="bank_account_bankily" placeholder="{{__('Bankily account number')}}" value="{{ $settings->bank_account_bankily }}"/>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label class="form-label" for="bank_account_sedad">{{__('Sedad account number')}} </label>
                    <input type="text" class="form-control" id="bank_account_sedad" name="bank_account_sedad" placeholder="{{__('Sedad account number')}}" value="{{ $settings->bank_account_sedad }}"/>
                  </div>
                </div>

                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label class="form-label" for="bank_account_bimbank">{{__('Bimbank Mobile account number')}} </label>
                    <input type="text" class="form-control" id="bank_account_bimbank" name="bank_account_bimbank" placeholder="{{__('Bimbank Mobile account number')}}" value="{{ $settings->bank_account_bimbank }}"/>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label class="form-label" for="bank_account_masrfy">{{__('Masrify account number')}} </label>
                    <input type="text" class="form-control" id="bank_account_masrfy" name="bank_account_masrfy" placeholder="{{__('Masrify account number')}}" value="{{ $settings->bank_account_masrfy }}"/>
                  </div>
                </div>

                <div class="mb-3" style="text-align: center">
                  <button type="submit" id="submit" name="submit" class="btn btn-primary update">{{__('Send')}}</button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('page-script')

<script>
$(document).ready(function() {



  $('#submit').on('click', function() {
      var queryString = new FormData($("#setting_form")[0]);
      console.log('this is match');
      console.log(queryString);
      $.ajax({
        url: '{{ url('/settings/update') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'POST',
        data:queryString,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
            if(response.status==1){
              Swal.fire(
                  "{{ __('Success') }}",
                  "{{ __('success') }}",
                  'success'
              ).then((result)=>{
                    location.reload();
                  });
            } else {
              console.log(response.message);
              Swal.fire(
                  "{{ __('Error') }}",
                  response.message,
                  'error'
              );
            }
        },
        error: function(data){
          var errors = data.responseJSON;
          console.log(errors);
          Swal.fire(
              "{{ __('Error') }}",
              errors.message,
              'error'
          );
          // Render the errors with js ...
        }
      });
  });
});
</script>
@endsection
