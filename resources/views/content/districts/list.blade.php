@extends('layouts/contentNavbarLayout')

@section('title', __('Districts'))

@section('content')

<div id="loading-spinner">
  <div class="spinner"></div>
</div>

<div id="loading-page" >
  {{-- Start header page wilaya --}}
  <h4 class="fw-bold py-3 mb-3 z-1  ">
    <span class="text-muted fw-light">{{__('Districts')}} /</span> {{__('Browse District')}}
    <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add District')}}</button>
  </h4>
  <!-- Basic Bootstrap Table -->
  <div class="card">
    <h5 class="card-header">{{__('District table')}}
      <select class="filter-select" id="wilaya" name="wilaya">
        <option value="" > {{__('wilaya filter')}}</option>
        @foreach ($wilayas as $wilaya)
          <option value="{{$wilaya->id}}" >{{$wilaya->name}} </option>
        @endforeach
      </select>

    </h5>

    <div class="table-responsive text-nowrap">
      <table class="table" id="laravel_datatable" style="width: 100%">
        <thead>
          <tr>
            <th>#</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Wilaya')}}</th>
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
          <h4 class="fw-bold py-1 mb-1">{{__('Add District')}}</h4>
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
                <label class="form-label" for="category_id">{{__('Wilaya')}}</label>
                <select class="form-select" id="wilaya_id" name="wilaya_id">
                  <option value="" > {{__('Select wilaya')}}</option>
                  @foreach ($wilayas as $wilaya)
                    <option value="{{$wilaya->id}}" > {{$wilaya->name}} </option>
                  @endforeach
                </select>
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
  $(document).ready(function()
  {
    // ajax Setup
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // Start list district table
    load_data();
    function load_data(wilaya = null) {
      var table = $('#laravel_datatable').DataTable({

          responsive: true,
          processing: true,
          serverSide: true,
          pageLength: 100,

          ajax: {
              url: "{{ url('/district/list') }}",
              data:{wilaya:wilaya},
              type: 'POST',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                  data: 'wilaya',
                  name: 'wilaya'
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
     // ajax loading page
    $(document).ajaxStart(function () {
      $('#loading-spinner').show();
      $('#loading-page').hide();
    });
    $(document).ajaxStop(function () {
      $('#loading-spinner').hide();
      $('#loading-page').show();
    });

    // Ajax for filter wilaya
    $('#wilaya').on('change', function() {
      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(document.getElementById('wilaya').value);
    });

    // Ajax show modal for create
    $('#create').on('click', function()
    {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });

    // Ajax for submit data for create or update
    $('#submit').on('click', function()
    {
      var formdata = new FormData($("#form")[0]);
      var formtype = document.getElementById('form_type').value;
      // insert data
      if(formtype == "create")
      {
        url = "{{ url('/district/create') }}";
      }
      // update data
      if(formtype == "update")
      {
        url = "{{ url('/district/update') }}";
        formdata.append("district_id",document.getElementById('id').value)
      }
      $.ajax({
        url: url,
        type:'POST',
        data:formdata,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response)
        {
          if (response.status == 1)
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
        error:function(data)
        {
          var errors = data.responseJSON;
          console.log(errors);
          Swal.fire(
              "{{ __('Error') }}",
              errors.message,
              'error'
          );
        }
      });
    });

    // ajax  open modal for update
    $(document.body).on('click', '.update', function()
    {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var district_id = $(this).attr('table_id');

      $("#id").val(district_id);

      $.ajax({
        url: '{{ url('/district/update') }}',
        type:'POST',
        data:{district_id: district_id},
        dataType : 'JSON',
        success:function(response)
        {
          if (response.status == 1)
          {
            // console.log(response.data);
            document.getElementById('name').value =  response.data.name;
            document.getElementById('wilaya_id').value = response.data.wilaya_id;
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

    // ajax foe deleted districts
    $(document.body).on('click', '.delete', function ()
    {
      var district_id = $(this).attr('table_id');

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
            url: "{{ url('/district/delete') }}",
            type:'POST',
            data:{district_id : district_id},
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
  });
</script>
@endsection
