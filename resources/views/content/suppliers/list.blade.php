@extends('layouts/contentNavbarLayout')

@section('title', __('Suppliers'))

@section('content')
<div id="loading-spinner" >
  <div class="spinner"></div>
</div>
<div id="loading-page" >
  <h4 class="fw-bold py-3 mb-3">
    <span class="text-muted fw-light">{{__('Suppliers')}} /</span> {{__('Browse Suppliers')}}
    <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add Supplier')}}</button>
  </h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <h5 class="card-header">{{__('Suppliers table')}}</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" id="laravel_datatable" style="width: 100%;">
        <thead>
          <tr>
            <th>#</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Phone')}}</th>
            {{-- <th>{{__('Email')}}</th> --}}
            <th>{{__('Status')}}</th>
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
          <h4 class="fw-bold py-1 mb-1">{{__('Add Supplier')}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="form_type" hidden />
          <input type="text" class="form-control" id="id" name="id" hidden/>
          <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                enctype="multipart/form-data" id="form">

              <div class="mb-3">
                <label class="form-label" for="fullname">{{__('Name')}}</label>
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="{{__('Name')}}"/>
              </div>
              <div class="mb-3">
                <label class="form-label" for="name">{{__('Phone')}}</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="{{__('Phone')}}"/>
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
  $(document).ready(function(){
    // ajax for loading page request rsponce
    $(document).ajaxStart(function () {
      $('#loading-spinner').show();
      $('#loading-page').hide();
    });

    $(document).ajaxStop(function () {
      $('#loading-spinner').hide();
      $('#loading-page').show();
    });
    // ajax Setup
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // aja dataTable list suppliers
    load_data();
    function load_data() {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,
            ajax: {
                url: "{{ url('supplier/list') }}",
            },
            type: 'GET',
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
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data){
                          if(data == false){
                              return '<span class="badge bg-danger">{{__("Inactive")}}</span>';
                            }else{
                              return '<span class="badge bg-success">{{__("Active")}}</span>';
                            }
                          }
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
    // ajax create or added a new supllier
    $('#create').on('click', function()
    {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });

    // ajax insert new supplier or update
    $('#submit').on('click', function()
    {
        var formdata = new FormData($("#form")[0]);
        var formtype = document.getElementById('form_type').value;
        // insert data
        if(formtype == "create"){
            url = "{{ url('/suppliers/create') }}";
          }
          // update data
          if(formtype == "update"){
            url = "{{ url('/suppliers/update') }}";
            formdata.append("supplier_id",document.getElementById('id').value)
          }
          $("#modal").modal("hide");

        $.ajax({
          url: url,
          type: 'POST',
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
            else
            {
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
    });

    // ajax for show details supplier for upadat
    $(document.body).on('click', '.update', function()
    {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var supplier_id = $(this).attr('table_id');
      $("#id").val(supplier_id);

      $.ajax({
        url: '{{url('suppliers/update')}}',
        type: 'POST',
        data:{supplier_id: supplier_id},
        dataType: 'json',
        success:function(response)
        {
          if (response.status == 1)
          {
            document.getElementById('fullname').value = response.data.fullname;
            document.getElementById('phone').value = response.data.phone;
            $('#modal').modal('show');

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

    $(document.body).on('click', '.delete', function() {

      var supplier_id = $(this).attr('table_id');

      Swal.fire({
        title: "{{ __('Warning') }}",
        text: "{{ __('Are you sure?') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{ __('Yes') }}",
        cancelButtonText: "{{ __('No') }}"
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "{{ url('suppliers/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              supplier_id : supplier_id,
              status : 0
            },
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    location.reload();
                  });
                }
              }
          });


        }
      })
    });

    $(document.body).on('click', '.restore', function() {

      var supplier_id = $(this).attr('table_id');

      Swal.fire({
        title: "{{ __('Warning') }}",
        text: "{{ __('Are you sure?') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{ __('Yes') }}",
        cancelButtonText: "{{ __('No') }}"
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "{{ url('suppliers/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              supplier_id : supplier_id,
              status : 1
            },
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    location.reload();
                  });
                }
              }
          });
        }
      })
    });


  });
</script>
@endsection

