@extends('layouts/contentNavbarLayout')

@section('title', __('Wilayas'))

@section('content')
<div id="loading-spinner">
  <div class="spinner"></div>
</div>

<div id="loading-page" >
  {{-- Start header page wilaya --}}
  <h4 class="fw-bold py-3 mb-3 z-1  ">
    <span class="text-muted fw-light">{{__('Wilayas')}} /</span> {{__('Browse wilayas')}}
    <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add walaya')}}</button>
  </h4>
  <!-- Basic Bootstrap Table -->
  <div class="card">
    <div class="card-header ">
      <h5 >{{__('Walayas table')}} </h5>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table" id="laravel_datatable" style="width: 100%">
        <thead>
          <tr>
            <th>#</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Delivery Pricce')}}</th>
            <th>{{__('Districts')}}</th>
            <th>{{__('Created at')}}</th>
            <th>{{__('Actions')}}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  {{-- Wilayas modal --}}
  <div class="modal fade" id="modal"  aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="fw-bold py-1 mb-1">{{__('Add walaya')}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="form_type" hidden />
          <input type="text" class="form-control" id="id" name="id" hidden/>
          <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
            enctype="multipart/form-data" id="form">

              <div class="mb-3">
                <label class="form-label" for="name">{{__('Name')}}</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Name')}}"/>
              </div>
              <div class="mb-3">
                <label class="form-label" for="delivery_price">{{__('Delivery Pricce')}}</label>
                <input type="text" class="form-control" id="delivery_price" name="delivery_price" placeholder="{{__('Delivery Pricce')}}"/>
              </div>

            <div class="mb-3" style="text-align: center">
              <button type="submit" id="submit" name="submit" class="btn btn-primary">{{__('Send')}}</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('page-script')
<script>
  $(document).ready(function ()
  {
    // ajax Setup
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Start list wilayas table
    load_data();
    function load_data() {
      var table = $('#laravel_datatable').DataTable({

          responsive: true,
          processing: true,
          serverSide: true,
          pageLength: 100,

          ajax: {
              url: "{{ url('/wilaya/list') }}",
          },

          columns: [

              {
                  data: 'DT_RowIndex',
                  name: 'DT_RowIndex'
              },

              {
                  data: 'name',
                  name: 'name'
              },

              {
                  data: 'delivery_pricce',
                  name: 'delivery_pricce'
              },
              {
                  data: 'district',
                  name: 'district'
              },

              {
                  data: 'created_at',
                  name: 'created_at'
              },

              {
                  data: 'action',
                  name: 'action',
                  render:function(data){
                    /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                      +data+'</div></div>' */
                      return '<span>'+data+'</span>';
                  }
              }

          ]
      });
    }
    // End list wilayas table

    // ajax loading page
    $(document).ajaxStart(function () {
      $('#loading-spinner').show();
      $('#loading-page').hide();
    });
    $(document).ajaxStop(function () {
      $('#loading-spinner').hide();
      $('#loading-page').show();
    });

    // while create new wilaya show modal
    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });

    $('#submit').on('click', function() {
      // ----------------------------------- Start ---------------------------------------------------------
        var formdata = new FormData($("#form")[0]);
        var formtype = document.getElementById('form_type').value;
          // insert data
          if(formtype == "create"){
            url = "{{ url('/wilaya/create') }}";
          }
          // update data
          if(formtype == "update"){
            url = "{{ url('/wilaya/update') }}";
            formdata.append("wilaya_id",document.getElementById('id').value)
          }
          $("#modal").modal("hide");
          // send data to wilayas controller
          $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'POST',
            data:formdata,
            dataType : 'JSON',
            contentType: false,
            processData: false,
            success:function(response)
            {
              if(response.status==1)
              {
                Swal.fire({
                  title: "{{ __('Success') }}",
                  text: "{{ __('success') }}",
                  icon: 'success',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  location.reload();
                });
              }
              else {
                console.log(response.message);
                Swal.fire(
                    "{{ __('Error') }}",
                    response.message,
                    'error'
                );
              }
            },
            error: function(data)
            {
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
      // ----------------------------------End -----------------------------------------------------------
    });

    // open modal for update wilaya
    $(document.body).on('click', '.update', function()
    {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var wilaya_id = $(this).attr('table_id');

      $("#id").val(wilaya_id);
      $.ajax({
        url: '{{ url('/wilaya/update') }}',
        type:'POST',
        data:{wilaya_id : wilaya_id},
        dataType : 'JSON',
        success:function(response)
        {
          if(response.status==1)
          {
            document.getElementById('name').value =  response.data.name;
            document.getElementById('delivery_price').value =  response.data.delivery_price;
            $("#modal").modal("show");
          }
          else
          {
            console.log(response.message);
            Swal.fire(
              "{{ __('Error') }}",
              response.message,
              'error'
            );
          }
        }

      });
    });

    //open modal for delete wilaya
    $(document.body).on('click', '.delete', function ()
    {
      var wilaya_id = $(this).attr('table_id');

      Swal.fire({
        title: "{{ __('Warning') }}",
        text: "{{ __('Are you sure?') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{ __('Delete') }}",
        cancelButtonText: "{{ __('Cancel') }}"
      })
      .then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/wilaya/delete') }}",
            type:'POST',
            data:{wilaya_id : wilaya_id},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){
                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  )
                  .then((result)=>{
                    location.reload();
                  });
                }else{
                  Swal.fire(
                    "{{ __('Error') }}",
                    response.message,
                    'error'
                  )
                }
              }
          });
        }
      })
    });
// --------------------------------------------- End ------------------------------------------------------------
});
</script>
@endsection

