@extends('layouts/contentNavbarLayout')

@section('title', __('Clients'))

@section('content')
<div id="loading-spinner" >
  <div class="spinner"></div>
</div>
<div id="loading-page" >
<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Clients')}} /</span> {{__('Browse clients')}}
  <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add client')}}</button>
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Clients table')}}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable" style="width: 100%;">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Phone')}}</th>
          <th>{{__('Wilaya')}}</th>
          <th>{{__('District')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- client modal --}}
<div class="modal fade" id="modal"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add client')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="form_type" hidden />
        <input type="text" class="form-control" id="id" name="id" hidden/>
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="form">
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <div hidden><img src="{{ asset('assets/img/icons/file-not-found.jpg') }}"  alt="image" class="d-block rounded" height="100" width="100" id="old-image"/> </div>
              <img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image" class="d-block rounded" height="100" width="100" id="uploaded-image" />
              <div class="button-wrapper">
                <label for="image" class="btn btn-primary" tabindex="0">
                  <span class="d-none d-sm-block">{{__('Upload new image')}}</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                  <input class="image-input" type="file" id="image" name="image" hidden accept="image/png, image/jpeg" />
                </label>
                <button type="button" class="btn btn-outline-secondary image-reset">
                  <i class="bx bx-reset d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">{{__('Reset')}}</span>
                </button>
                <br>
                {{-- <small class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</small> --}}
              </div>
            </div>
          </div>
          <hr class="my-0">

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Name')}}</label>
                <input type="text" class="form-control" id="name" name="name"/>
            </div>

            <div class="mb-3">
              <label class="form-label" for="phone">{{__('Phone')}}</label>
              <input type="number" class="form-control" id="phone" name="phone"/>
            </div>

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Location')}}</label>
              <div class="input-group input-group-merge">
                <select class="form-select" id="wilaya_id" name="wilaya_id">
                  <option value="">{{__('Select wilaya')}}</option>
                  @foreach ($wilayas as  $wilaya)
                    <option value="{{$wilaya->id}}">{{$wilaya->name}}</option>
                  @endforeach
                </select>

                <select class="form-select" id="district_id" name="district_id">
                  <option value="">{{__('Select wilaya first')}}</option>
                </select>


              </div>
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
    load_data();
    function load_data() {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('client/list') }}",
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
                    data: 'phone',
                    name: 'phone'
                },

                {
                    data: 'wilaya',
                    name: 'wilaya'
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

    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('uploaded-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}";
      document.getElementById('old-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}";
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });


    $(document.body).on('click', '.update', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var client_id = $(this).attr('table_id');
      $("#id").val(client_id);

      $.ajax({
          url: '{{ url('client/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{client_id : client_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                document.getElementById('name').value =  response.data.name;
                document.getElementById('phone').value =  response.data.phone;

                var image = response.data.image == null ?
                "{{ asset('assets/img/icons/file-not-found.jpg') }}" : response.data.image;

                document.getElementById('uploaded-image').src = image;
                document.getElementById('old-image').src = image;

                document.getElementById('wilaya_id').value = response.data.wilaya_id;

                $('#wilaya_id').trigger("change",function(){
                  document.getElementById('district_id').value = response.data.district_id;
                });

                $("#modal").modal("show");
              }
            }
        });
    });

    $('#submit').on('click', function() {

      var formdata = new FormData($("#form")[0]);
       var formtype = document.getElementById('form_type').value;
       console.log(formtype);
       if(formtype == "create"){
        url = "{{ url('client/create') }}";
       }

      if(formtype == "update"){
        url = "{{ url('client/update') }}";
        formdata.append("client_id",document.getElementById('id').value)
      }

      $("#modal").modal("hide");


      $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        data:formdata,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
          if(response.status==1){
                Swal.fire({
                  title: "{{ __('Success') }}",
                  text: "{{ __('success') }}",
                  icon: 'success',
                  confirmButtonText: 'Ok'
                }).then((result) => {
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

    $(document.body).on('click', '.delete', function() {

      var client_id = $(this).attr('table_id');

      Swal.fire({
        title: "{{ __('Warning') }}",
        text: "{{ __('Are you sure?') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{ __('Delete') }}",
        cancelButtonText: "{{ __('Cancel') }}"
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "{{ url('client/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{client_id : client_id},
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


    $('#wilaya_id').on('change', function(e, callback) {
      var wilaya_id = document.getElementById('wilaya_id').value;
      $.when(
      $.ajax({
        url: '{{ url('district/get?all=1') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{wilaya_id : wilaya_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                var districts = document.getElementById('district_id');
                districts.innerHTML = '<option value="">{{__("Not selected")}}</option>';

                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    districts.appendChild(option);
                }

              }
            }
        })
      ).done(function(a1, a2){
        callback();
      });



    });

    $(document.body).on('change', '.image-input', function() {
        const fileInput = document.querySelector('.image-input');
        if (fileInput.files[0]) {
          document.getElementById('uploaded-image').src = window.URL.createObjectURL(fileInput.files[0]);
        }
    });
    $(document.body).on('click', '.image-reset', function() {
      const fileInput = document.querySelector('.image-input');
      fileInput.value = '';
      document.getElementById('uploaded-image').src = document.getElementById('old-image').src;
    });
  });
</script>
@endsection
