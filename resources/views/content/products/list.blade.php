@extends('layouts/contentNavbarLayout')

@section('title', __('Products'))

@section('content')

  <div id="loading-spinner">
    <div class="spinner"></div>
  </div>

<div id="loading-page">
  <h4 class="fw-bold py-3 mb-3">
    <span class="text-muted fw-light">{{__('Products')}} /</span> {{__('Browse products')}}
    <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add Product')}}</button>
  </h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <div class="card-header  justify-content-between align-items-center">
      <div class="row">
        <div class="col-sm-4">
          <h5 >{{__('Products table')}} </h5>
        </div>
        <div class="col-sm-8">
          <div class="row w-100">
           {{--  <select class="filter-select col-md-4" id="discount" name="discount">
              <option value="" > {{__('Discount filter')}}</option>
              <option value="1" > {{__('Discounted')}}</option>
              <option value="2" > {{__('Not discounted')}}</option>
            </select> --}}

            <select class="filter-select col-md-4" id="subcategory" name="subcategory">
              <option value="" > {{__('Subcategory filter')}} </option>
            </select>

            <select class="filter-select col-md-4" id="category" name="category">
              <option value="" > {{__('Category filter')}}</option>
              @foreach ($categories as $category)
                <option value="{{$category->id}}" > {{$category->name}} </option>
              @endforeach
            </select>
          </div>
        </div>

      </div>
    </div>

      <div class="table-responsive text-nowrap">
      <table class="table" id="laravel_datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Purchasing price')}}</th>
            <th>{{__('Selling price')}}</th>
            <th>{{__('Quantity')}}</th>
            <th>{{__('Created at')}}</th>
{{--             <th>{{__('in_discount')}}</th>
            <th>{{__('discount')}}</th> --}}
            <th>{{__('Actions')}}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  {{-- product modal --}}
  <div class="modal fade" id="modal"  aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="fw-bold py-1 mb-1">{{__('Add Product')}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="form_type" hidden />
          <input type="text" class="form-control" id="id" name="id" hidden/>
          <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
            enctype="multipart/form-data" id="form">
            <div class="row">
              <div class="col-md-6">
                <div class="card-body">
                  <div class="d-flex  align-items-center align-items-sm-center gap-4">
                    <div hidden><img src="{{ asset('assets/img/icons/file-not-found.jpg') }}"  alt="image" class="d-block rounded" height="100" width="100" id="old-image"/> </div>
                    <img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image" class=" rounded" height="100" width="100" id="uploaded-image" />
                    {{-- Start button add multe image --}}
                    <div class=" mx-auto mb-3">
                      <label for="imageInput" class="btn btn-primary text-center" tabindex="0">
                        <span class="d-none d-sm-block">{{__('Upload new image')}}</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        <input type="file" id="imageInput"  name="image[]" hidden accept="image/png, image/jpeg" multiple>
                      </label>
                    </div>
                    {{-- End button add multe image --}}

                  </div>
                  {{-- Start swiper image "add mulite" --}}
                  <div class="swiper-body">
                    <div class="swiper mySwiper">
                      <div class="swiper-wrapper" id="swiperWrapper">
                        {{-- <div class="swiper-slide" ></div> --}}
                      </div>
                      <div class="swiper-pagination"></div>
                      <!-- Add Navigation -->
                      <div class="swiper-button-next"></div>
                      <div class="swiper-button-prev"></div>
                    </div>
                    {{-- end swiper image "add mulite" --}}
                  </div>
                  {{-- start button for upload new video  --}}
                    <div class="d-flex align-items-start align-items-sm-center gap-4 mt-4">
                      <img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image" class=" rounded" height="100" width="100" id="uploaded-video" />
                      {{-- Start button add mulite videos --}}
                      <div class="mx-auto mb-3">
                        <label for="videoInput" class="btn btn-primary text-center" tabindex="0">
                          <span class="d-none d-sm-block">{{__('Upload new video')}}</span>
                          <i class="bx bx-upload d-block d-sm-none"></i>
                          <input type="file" id="videoInput" name="video[]" class="video-input" hidden max="10485760" accept="video/mp4" multiple>
                        </label>
                      </div>
                      {{-- end button add mulite videos --}}
                    </div>

                    {{-- Start swiper رvideos "add mulite videos" --}}
                    <div class="swiper-body">
                      <div class="swiper swiper-container">
                        <div class="swiper-wrapper" id="swiperWrapperVideos">
                          <div class="swiper-slide" >
                          </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <!-- Add Navigation -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                      </div>
                    </div>
                    {{-- end swiper رvideos "add mulite videos" --}}
                  {{-- End button for upload new video  --}}
                </div>
                <hr class="my-2">

                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Name')}}</label>
                  <div class="input-group input-group-merge">
                    <input dir="rtl" type="text" class="form-control" id="unit_name" name="unit_name" value="{{ old('unit_name')}}" placeholder="{{__('Unit name')}}"/>
                    <input dir="rtl" type="text" class="form-control" id="pack_name" name="pack_name" value="{{ old('pack_name')}}" placeholder="{{__('Pack name')}}"/>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="supplier">{{__('Name Supplier')}}</label>
                  <select class="form-select supp" id="supplier_id" name="supplier_id"  placeholder="{{__('selected supplier')}}">
                    @foreach ($suppliers as $supplier)
                      <option value="{{ $supplier->id}}">{{ $supplier->fullname}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="supplier">{{__('Code Supplier')}}</label>
                    <input type="text" id="code_supplier" name="code_supplier" value="{{ old('code_supplier') }}" class="form-control" placeholder="{{__('Code Supplier')}}">
                </div>

                {{-- <div class="mb-3">
                  <label for="supplier">{{__('Code Bar')}}</label>
                    <input type="text" id="code_bar" name="code_bar" class="form-control" placeholder="{{__('Code Bar')}}">
                </div> --}}

                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Quantity')}}</label>
                  <div class="input-group input-group-merge">
                    <input type="text" class="form-control" id="quantity" name="quantity" value="{{ old('quantity')}}"/>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Purchasing Price')}}</label>
                  <div class="input-group input-group-merge">
                    <input type="text" class="form-control" id="purchasing_price" name="purchasing_price" value="{{ old('purchasing_price')}}" placeholder="{{__('Purchasing Price')}}"/>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Selling price')}}</label>
                  <div class="input-group input-group-merge">
                    <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price')}}" placeholder="{{__('Unit price')}}"/>
                    <input type="text" class="form-control" id="pack_price" name="pack_price" value="{{ old('pack_price')}}" placeholder="{{__('Pack price')}}"/>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Pack units')}}</label>
                    <input type="number" class="form-control" id="pack_units" name="pack_units" value="{{ old('pack_units')}}" placeholder="{{__('Pack units')}}"/>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="unit_type">{{__('Unit type')}}</label>
                    <select class="form-select" id="unit_type" name="unit_type">
                      <option value="1" > {{__('Piece')}}</option>
                      <option value="2" > {{__('100 gram')}}</option>
                      <option value="3" > {{__('1 kilogram')}}</option>
                    </select>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Subcategory')}}</label>
                  <div class="input-group input-group-merge">
                    <select class="form-select" id="category_id">
                      <option value="" > {{__('Select category')}}</option>
                      @foreach ($categories as $category)
                        <option value="{{$category->id}}" > {{$category->name}} </option>
                      @endforeach
                    </select>
                    <select class="form-select" id="subcategory_id" name="subcategory_id">
                        <option value="" > {{__('Select category first')}} </option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="" class="form-label">{{__('وصف منتج')}}</label>
                  <textarea name="description" id="description" class="form-control">{{ old('description')}}</textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="name">{{__('Status')}}</label>
                    <select class="form-select" id="status" name="status">
                      <option value="1" > {{__('Available')}}</option>
                      <option value="2" > {{__('Unavailable')}}</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="mb-3" style="text-align: center">
              <button type="submit" id="submit" name="submit" class="btn btn-primary submitImage">{{__('Send')}}</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- discount modal --}}
  <div class="modal fade" id="discount_modal"  aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="fw-bold py-1 mb-1">{{__('Add discount')}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="discount_form_type" hidden />
          <input type="text" class="form-control" id="discount_id" name="discount_id" hidden/>
          <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
            enctype="multipart/form-data" id="discount_form">

            <input type="text" class="form-control" id="product_id" name="product_id" hidden/>

            <div class="mb-3">
              <label class="form-label" for="type">{{__('Type')}}</label>
                <select class="form-select" id="type" name="type">
                  <option value="1" > {{__('Fixed')}}</option>
                  <option value="2" > {{__('Percentage')}}</option>
                </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Discount amount')}}</label>
              <input type="text" class="form-control" id="amount" name="amount"/>
            </div>

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Start date')}}</label>
              <input type="date" class="form-control" id="start_date" name="start_date"/>
            </div>

            <div class="mb-3">
              <label class="form-label" for="name">{{__('End date')}}</label>
              <input type="date" class="form-control" id="end_date" name="end_date"/>
            </div>


            <div class="mb-3" style="text-align: center">
              <button type="submit" id="submit_discount" name="submit_discount" class="btn btn-primary">{{__('Send')}}</button>
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
    var imagesArray = [];
    var imagesInc   = [];
    var videosArray = [];
    var videosInc   = [];
    // Add mulite image in swiper ---------------------------
    var swiper = new Swiper(".mySwiper",
    {
      slidesPerView: 3,
      spaceBetween: 30,
      pagination:
      {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation:
      {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
    var inputimages = document.getElementById('imageInput');
    inputimages.addEventListener('input', setInputImage);
    // document.addEventListener('input', )
    function setInputImage()
    {
      var input = document.getElementById('imageInput');
      var wrapper = document.getElementById('swiperWrapper');
      var imageNotFound = document.getElementById('uploaded-image');

      for (var i = 0; i < input.files.length; i++)
      {
        var reader = new FileReader();
        reader.onload = function (e)
        {
          imageNotFound.style.display = 'none';
          var slide = document.createElement('div');
          slide.className = 'swiper-slide';
          slide.innerHTML = '<img src="' + e.target.result + '" alt="Image">';
          slide.innerHTML += '<button type="button" class="btn-close btn-danger" onclick="removeSlideImage(this)"></button>';
          wrapper.appendChild(slide);
          swiper.update(); // Update Swiper after adding a new slide
          // imagesArray.push(e.target.result);
        };
        reader.readAsDataURL(input.files[i]);
        imagesArray.push(input.files[i]);
        imagesInc.push(i);

      };
    };
    function removeSlideImage(button, image_id)
    {
      var slide = button.parentNode;
      var index = Array.from(document.getElementsByClassName("swiper-slide")).indexOf(slide);
      if (image_id)
      {
        $(document).on('click', '.delete-image', function(e)
        {
          e.preventDefault();
          var deleteImage = document.getElementsByClassName('delete-image');
          Swal.fire({
            title: "{{ __('Warning') }}",
            text: "{{ __('Do you sure delete this image?') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{ __('Delete') }}",
            cancelButtonText: "{{ __('Cancel') }}"
          })
          .then((result) =>
          {
            if (result.isConfirmed)
            {
              imagesArray.splice(index, 1);// remove image in array;
              slide.remove();
              swiper.update(); // Update Swiper after removing a slide
              $.ajax({
                url: "{{ url('product/delete-media') }}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:'POST',
                data:{product_media_id : image_id},
                dataType : 'JSON',
                success:function(response)
                {
                  if(response.status==1)
                  {
                    Swal.fire(
                      "{{ __('Success') }}",
                      "{{ __('success') }}",
                      'success'
                    ).then((result)=>{
                      // location.reload();
                    });
                  }
                }
              });
            }
          });
        });
      }
      else
      {
        imagesArray.splice(index, 1);// remove image in array;
        slide.remove();
        swiper.update(); // Update Swiper after removing a slide
      }
    }
    // -----------------------------------------------------
    // add multe videos in siwper --------------------------
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    var inputvideos = document.getElementById('videoInput');
    inputvideos.addEventListener('input', setInputVideos);
    // document.addEventListener('input', setInputVideos);
    function setInputVideos()
    {
        var input = document.getElementById('videoInput');
        var wrapper = document.getElementById('swiperWrapperVideos');
        var videoNotFoud = document.getElementById('uploaded-video');
        for (var i = 0; i < input.files.length; i++) {
          var reader = new FileReader();
          reader.onload = function (e) {
            videoNotFoud.style.display = 'none';
              var slideVideo = document.createElement('div');
              slideVideo.className = 'swiper-slide';
              slideVideo.innerHTML = '<video controls muted><source src="' + e.target.result + '" type="video/mp4"></video>';
              slideVideo.innerHTML += '<button type="button" class="btn-close btn-danger"  aria-label="Close" onclick="removeSlide(this)"></button>';
              wrapper.appendChild(slideVideo);
              swiper.update(); // Update Swiper after adding a new slide
          };

          reader.readAsDataURL(input.files[i]);
          videosArray.push(input.files[i]);
          videosInc.push(i);
        }
    };

    function removeSlide(button, video_id) {
      var slide = button.parentNode;
      var index = Array.from(document.getElementsByClassName("swiper-slide")).indexOf(slide);
      if (video_id)
      {
        $(document).on('click', '.delete-video', function(e)
        {
          e.preventDefault();
          var deleteImage = document.getElementsByClassName('delete-video');
          Swal.fire({
            title: "{{ __('Warning') }}",
            text: "{{ __('Do you sure delete this video?') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{ __('Delete') }}",
            cancelButtonText: "{{ __('Cancel') }}"
          })
          .then((result) =>
          {
            if (result.isConfirmed)
            {
              videosArray.splice(index, 1);// remove image in array;
              slide.remove();
              swiper.update(); // Update Swiper after removing a slide
              $.ajax({
                url: "{{ url('product/delete-media') }}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:'POST',
                data:{product_media_id : video_id},
                dataType : 'JSON',
                success:function(response)
                {
                  if(response.status==1)
                  {
                    Swal.fire(
                      "{{ __('Success') }}",
                      "{{ __('success') }}",
                      'success'
                    ).then((result)=>{
                      // location.reload();
                    });
                  }
                }
              });
            }
          });
        });
      }
      else
      {
        videosArray.splice(index, 1);// remove image in array;
        slide.remove();
        swiper.update(); // Update Swiper after removing a slide
        // console.log(videosArray);
      }
    }
    // -----------------------------------------------------

  $(document).ready(function () {
    // var form = document.querySelector('form');
    var formData = new FormData($('#form')[0]);
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // ajax for loading page request rsponce
    $(document).ajaxStart(function () {
      $('#loading-spinner').show();
      $('#loading-page').hide();
    });

    $(document).ajaxStop(function () {
      $('#loading-spinner').hide();
      $('#loading-page').show();
    });


    $('#form').on(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      // append image files to controller
      for (let i = 0; i < imagesInc.length; i++) {
          formData.append('files' + i, imagesArray[i]);
      }
      formData.append('imagesInc', imagesInc.length);
      // append video files to controller
      for (let i = 0; i < videosInc.length; i++) {
          formData.append('filesvideos' + i, videosArray[i]);
      }
      formData.append('videosInc', videosInc.length);

    });
    //------------ select search name suppliers and selected or add new name suppliers --------------------------
    new TomSelect("#select-tags", {
      plugins: ['remove_button'],
      create: true,
      onItemAdd: function() {
        this.setTextboxValue('');
        this.refreshOptions();
      },
      render: {
        option: function(data, escape) {
          return '<div class="d-flex"><span>' + escape(data.value) + '</span><span class="ms-auto text-muted">' + escape(data.date) + '</span></div>';
        },
        item: function(data, escape) {
          return '<div>' + escape(data.value) + '</div>';
        }
      }
    })
  });

  $(document).ready(function(){


    load_data();
    // ajax setup
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function load_data(category = null,subcategory=null , discount=null)
    {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('product/list') }}",
                data:{
                  category:category,
                  subcategory:subcategory,
                  discount:discount
                },
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
                    data: 'purchasing_price',
                    name: 'purchasing_price'
                },

                {
                    data: 'selling_price',
                    name: 'selling_price'
                },

                {
                    data: 'quantity',
                    name: 'quantity'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                /* {
                    data: 'is_discounted',
                    name: 'is_discounted',
                    render: function(data){
                      if(data == false){
                          return '<span class="badge bg-danger">{{__("No")}}</span>';
                        }else{
                          return '<span class="badge bg-success">{{__("Yes")}}</span>';
                        }
                      }
                },


                {
                    data: 'discount',
                    name: 'discount'
                }, */


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

    $('#category').on('change', function()
    {
      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;
      console.log('ahwadjyana',category_id);

      $.ajax({
          url: '{{ url('subcategory/get?all=1') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{category_id : category_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){
                console.log(response.data);
                var subcategories = document.getElementById('subcategory');
                subcategories.innerHTML = '<option value="">{{__("Not selected")}}</option>';
                console.log(response.data);
                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    subcategories.appendChild(option);
                }

              }
            }
        });


      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount);
    });

    $('#subcategory').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount);

    });

    $('#discount').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount);

    });

    // $('#unit_name').on('blur', function() {

    //   var unit_name = document.getElementById('unit_name').value;

    //   document.getElementById('pack_name').value = ' (حزمة) '+ unit_name;


    // });


    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      document.getElementById('uploaded-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}" ;
      document.getElementById('old-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}" ;
      $('#true-select').show();
      $('#false-select').hide();
      $("#modal").modal('show');

    });


    $(document.body).on('click', '.update', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var product_id = $(this).attr('table_id');
      $("#id").val(product_id);
      // console.log('id prod', product_id);
      $.ajax({
          url: '{{ url('product/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{product_id : product_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){
                // console.log(response.data);

                var wrapperimages = document.getElementById('swiperWrapper');
                var wrappervideos = document.getElementById('swiperWrapperVideos');
                var uploadedImage = document.getElementById('uploaded-image');
                var uploadedVideo = document.getElementById('uploaded-video');

                document.getElementById('unit_name').value =  response.data.unit_name;
                document.getElementById('pack_name').value =  response.data.pack_name;
                document.getElementById('unit_price').value =  response.data.unit_price;
                document.getElementById('pack_price').value =  response.data.pack_price;
                document.getElementById('pack_units').value =  response.data.pack_units;
                document.getElementById('purchasing_price').value =  response.data.purchasing_price;
                document.getElementById('unit_type').value =  response.data.unit_type;
                document.getElementById('quantity').value =  response.data.stock;
                document.getElementById('description').value =  response.data.description;
                document.getElementById('code_supplier').value =  response.data.code_supplier;
                // document.getElementById('code_bar').value =  response.data.code_bar;
                document.getElementById('status').value =  response.data.status == 'available' ? 1 : 2 ;

                var nameSuplier = response.data.name_supplers == null ? ' لا يوجد مورد أو تم حذفه  '
                                                                      : response.data.name_supplers;
                document.getElementById('supplier_id').value =  response.data.supplier_id;

                var images = response.data.images;
                var videos = response.data.videos;
                images.length == 0 ? uploadedImage.style.display = 'block' : uploadedImage.style.display = 'none';
                videos.length == 0 ? uploadedVideo.style.display = 'block' : uploadedVideo.style.display = 'none';

                wrapperimages.innerHTML = '';

                images.forEach(image => {
                  var slideI = document.createElement('div');
                      slideI.className = 'swiper-slide';
                      slideI.innerHTML = '<img src="' + image.images + '" alt="Image">';
                      slideI.innerHTML += '<button type="button" class="btn-close btn-danger delete-image" onclick="removeSlideImage(this,'+image.id +')"></button>'
                      wrapperimages.appendChild(slideI);
                    });

                  wrappervideos.innerHTML = '';

                  videos.forEach(video => {
                    var slideVideo = document.createElement('div');
                      // wrappervideos.appendChild(slideVideo);
                      slideVideo.className = 'swiper-slide';
                      slideVideo.innerHTML = '<video controls="true" ><source src="' + video.videos + '" type="video/mp4"></video>';
                      slideVideo.innerHTML += '<button type="button" class="btn-close btn-danger delete-video"  aria-label="Close" onclick="removeSlide(this,'+ video.id +')"></button>';
                      wrappervideos.appendChild(slideVideo);
                  });

                // console.log(response.data.category_id);
                document.getElementById('category_id').value = response.data.category_id;

                $('#category_id').trigger("change",function(){
                  document.getElementById('subcategory_id').value = response.data.subcategory_id;
                });

                $("#modal").modal("show");
              }
            }
        });
    });

    $('#category_id').on('change', function(e, callback) {
      var category_id = document.getElementById('category_id').value;
      console.log('categories ahwadj',category_id);
      $.when(
      $.ajax({
        url: '{{ url('subcategory/get?all=1') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{category_id : category_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                var subcategories = document.getElementById('subcategory_id');
                subcategories.innerHTML = '<option value="">{{__("Not selected")}}</option>';

                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    subcategories.appendChild(option);
                }

              }
            }
        })
      ).done(function(a1, a2){
        callback();
      });



    });

    $('#submit').on('click', function() {

      /* var formdata = new FormData($("#form")[0]); */
      var queryString = new FormData($("#form")[0]);
      // console.log('queryStrig' ,formdata.entries());

      var formtype = document.getElementById('form_type').value;

      if(formtype == "create")
      {
        url = "{{ url('product/create') }}";
        for (let i = 0; i < imagesInc.length; i++)
        {
          queryString.append('files' + i, imagesArray[i]);
        }
        queryString.append('imagesInc', imagesInc.length);
        // append video files to controller
        for (let i = 0; i < videosInc.length; i++)
        {
            queryString.append('filesvideos' + i, videosArray[i]);
        }
        queryString.append('videosInc', videosInc.length);
      }

      if(formtype == "update")
      {
        url = "{{ url('product/update') }}";
        queryString.append("product_id",document.getElementById('id').value);
        // console.log('update',imagesArray);
        // console.log('update',videosArray);
        // console.log('update i:',imagesInc);

        for (let i = 0; i < imagesInc.length; i++)
        {
          queryString.append('files' + i, imagesArray[i]);
        }
        queryString.append('imagesInc', imagesInc.length);
        // append video files to controller
        for (let i = 0; i < videosInc.length; i++)
        {
            queryString.append('filesvideos' + i, videosArray[i]);
        }
        queryString.append('videosInc', videosInc.length);

      }

      $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        data:queryString,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
          if(response.status==1)
          {
            $("#modal").modal("hide");
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

    $(document.body).on('click', '.delete', function()
    {

      var product_id = $(this).attr('table_id');

      Swal.fire({
          title: "{{ __('Warning') }}",
          text: "{{ __('Are you sure?') }}",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: "{{ __('Delete') }}",
          cancelButtonText: "{{ __('Cancel') }}"
        }).then((result) =>
        {
          if (result.isConfirmed)
          {
            $.ajax({
              url: "{{ url('product/delete') }}",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type:'POST',
              data:{product_id : product_id},
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

    $(document.body).on('click', '.add_discount', function() {
      var product_id = $(this).attr('table_id');
      document.getElementById('discount_form').reset();
      document.getElementById('discount_form_type').value = "create";
      document.getElementById('product_id').value = product_id;
      $("#discount_modal").modal('show');
    });


    $(document.body).on('click', '.edit_discount', function() {
      document.getElementById('discount_form').reset();
      document.getElementById('discount_form_type').value = "update";
      var discount_id = $(this).attr('table_id');
      $("#discount_id").val(discount_id);

      $.ajax({
          url: '{{ url('discount/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{discount_id : discount_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                document.getElementById('product_id').value =  response.data.product_id;
                document.getElementById('amount').value =  response.data.amount;
                document.getElementById('start_date').value =  response.data.start_date;
                document.getElementById('end_date').value =  response.data.end_date;
                document.getElementById('start_date').readOnly = true;
                document.getElementById('type').value = 2 ;

                $("#discount_modal").modal("show");
              }
            }
        });
    });

    $('#submit_discount').on('click', function() {

      var formdata = new FormData($("#discount_form")[0]);
      var formtype = document.getElementById('discount_form_type').value;
      // console.log(formtype);
      if(formtype == "create"){
        url = "{{ url('discount/create') }}";
      }

      if(formtype == "update"){
        url = "{{ url('discount/update') }}";
        formdata.append("discount_id",document.getElementById('discount_id').value)
      }

      $("#discount_modal").modal("hide");


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

      $(document.body).on('click', '.delete_discount', function()
      {

        var discount_id = $(this).attr('table_id');

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
              url: "{{ url('discount/delete') }}",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type:'POST',
              data:{discount_id : discount_id},
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
