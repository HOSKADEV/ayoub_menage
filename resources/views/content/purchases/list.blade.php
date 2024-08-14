@extends('layouts/contentNavbarLayout')

@section('title', __('Purchases'))

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-repeater/dist/js/repeater.min.js"></script> --}}
@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-3">
        <span class="text-muted fw-light">{{ $supplier->fullname }} /</span> {{ __('Purchases') }}
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal"
            style="float:right">{{ __('Add purchase') }}</button>
        <input type="hidden" id="supplier_id" value="{{ $supplier->id }}" />
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">{{ __('Purchases table') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Total amount') }}</th>
                        <th>{{ __('Paid amount') }}</th>
                        <th>{{ __('Debt amount') }}</th>
                        <th>{{ __('Purchase items') }}</th>
                        <th>{{ __('Receipt') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1">{{ __('Create purchase') }}</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal" onsubmit="event.preventDefault()" action="#" enctype="multipart/form-data"
                    id="create_form">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-5">
                                <label for="level" class="form-label">{{ __('Paid amount') }}</label>
                                <input class="form-control" type="number" name="paid_amount" />
                            </div>
                            <div class="col-5">
                                <label for="level" class="form-label">{{ __('Receipt') }}</label>
                                <input class="form-control" type="file" name="receipt" />
                            </div>
                        </div>


                        <div class="repeater" id="repeater">
                            <div data-repeater-list="items">
                                <div data-repeater-item class="row mb-3">
                                    <div class="col-5">
                                        <label for="product" class="form-label">{{ __('Product') }}</label>

                                        <select class="selectpicker form-control purchase-item" data-dropup-auto="false" data-live-search="true" name="product_id">
                                            <option selected disabled>{{ __('Select product') }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    price="{{ $product->purchasing_price }}">{{ $product->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input class="form-control" type="hidden" name="name" />
                                    </div>
                                    <div class="col-3">
                                        <label for="price" class="form-label">{{ __('Price') }}</label>
                                        <input class="form-control" type="number" name="price" />
                                    </div>

                                    <div class="col-2">
                                        <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                                        <input class="form-control" type="number" name="quantity" />
                                    </div>


                                    <div class="col-2">
                                        <label for="quantity" class="form-label">{{ __('Actions') }}</label></br>
                                        <button type="button" class="btn btn-md btn-danger btn-block" data-repeater-delete>
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-md btn-primary btn-block" data-repeater-create>
                                        <i class='tf-icons bx bx-plus'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" style="text-align: center">
                        <button type="submit" id="create_submit" name="submit"
                            class="btn btn-primary">{{ __('Send') }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- edit modal --}}
<div class="modal fade" id="edit_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('update purchase')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="edit_form">

          {{-- <input type="text" class="form-control" name="purchase_id" value="{{$purchase->id}}" hidden/> --}}

          <input type="text" class="form-control" id="purchase_id" name="purchase_id" hidden/>

          <div class="mb-3">
            <label for="level" class="form-label">{{ __('Paid amount') }}</label>
            <input class="form-control" type="number" id="paid_amount" name="paid_amount" />
        </div>
        <div class="mb-3">
            <label for="level" class="form-label">{{ __('Receipt') }}</label>
            <input class="form-control" type="file" name="receipt" />
        </div>



          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_edit" name="submit_edit" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // ajax for loading page request rsponce
            $(document).ajaxStart(function() {
                $('#loading-spinner').show();
                $('#loading-page').hide();
            });

            $(document).ajaxStop(function() {
                $('#loading-spinner').hide();
                $('#loading-page').show();
            });

            load_data();

            function load_data() {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var supplier_id = document.getElementById('supplier_id').value;
                var table = $('#laravel_datatable').DataTable({

                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 100,

                    ajax: {
                        url: "{{ url('purchase/list') }}",
                        data: {
                            supplier_id: supplier_id
                        },
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },



                    columns: [

                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },

                        {
                            data: 'total_amount',
                            name: 'total_amount'
                        },

                        {
                            data: 'paid_amount',
                            name: 'paid_amount'
                        },

                        {
                            data: 'debt_amount',
                            name: 'debt_amount',
                            render: function(data) {
                                if (data < 0) {
                                  return  '<span class="text-danger">'+ new Intl.NumberFormat().format(data) +' Dzd</span>';
                                } else {
                                  return  '<span class="text-success">'+ new Intl.NumberFormat().format(data) +' Dzd</span>';
                                }
                            }
                        },

                        {
                            data: 'items',
                            name: 'items'
                        },

                        {
                            data: 'file',
                            name: 'file',
                            render: function(data) {
                                if (data) {
                                    return '<a href=' + data +
                                        '><i class="bx bx-image me-2"></i><span class="align-middle">{{ __('show file') }}</span></a>';
                                } else {
                                    return '<a>' + '{{ __('file does not exist') }}' +
                                        '</span></a>';
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
                            render: function(data) {
                                /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                                  +data+'</div></div>' */
                                return '<span>' + data + '</span>';
                            }
                        }

                    ]
                });
            }

            $('#create').on('click', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "create";
                document.getElementById('uploaded-image').src =
                    "{{ asset('assets/img/icons/ad-not-found.jpg') }}";
                document.getElementById('old-image').src =
                    "{{ asset('assets/img/icons/ad-not-found.jpg') }}";
                $("#modal").modal('show');
            });


            $(document.body).on('click', '.update', function() {
                //document.getElementById('form').reset();
                //document.getElementById('form_type').value = "update";
                var purchase_id = $(this).attr('table_id');
                $("#purchase_id").val(purchase_id);

                $.ajax({
                    url: '{{ url('purchase/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        purchase_id: purchase_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {

                            document.getElementById('paid_amount').value = response.data.paid_amount;

                            $("#edit_modal").modal("show");
                        }
                    }
                });
            });

            $('#submit_edit').on('click', function() {

                var formdata = new FormData($("#edit_form")[0]);
                //var formtype = document.getElementById('form_type').value;
                //console.log(formtype);
                /* if (formtype == "create") {
                    url = "{{ url('purchase/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('purchase/update') }}";
                    formdata.append("purchase_id", document.getElementById('id').value)
                } */

                $("#edit_modal").modal("hide");


                $.ajax({
                    url: "{{ url('purchase/update') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formdata,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
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
                    error: function(data) {
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

            $('#create_submit').on('click', function() {

                var formdata = new FormData($("#create_form")[0]);
                formdata.append('supplier_id',document.getElementById('supplier_id').value);

                //$("#modal").modal("hide");


                $.ajax({
                    url : "{{ url('purchase/create') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formdata,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
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
                    error: function(data) {
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

                var purchase_id = $(this).attr('table_id');

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
                            url: "{{ url('purchase/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                purchase_id: purchase_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {

                                    Swal.fire(
                                        "{{ __('Success') }}",
                                        "{{ __('success') }}",
                                        'success'
                                    ).then((result) => {
                                        location.reload();
                                    });
                                }
                            }
                        });


                    }
                })
            });

            $(document.body).on('change', '.purchase-item', function(event) {
                var select = event.target;
                var option = select.options[select.selectedIndex];
                var value = select.value;
                var name = option.text;
                var price = option.getAttribute('price');
                //console.log(select.name.replace('id','price'));
                $("[name='" + select.name.replace('product_id', 'name') + "']").val(name);
                $("[name='" + select.name.replace('product_id', 'price') + "']").val(price);

            });
        });
        $('#repeater').repeater({
                show: function() {
                  $('.purchase-item').selectpicker('render');
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                    // if(confirm('Are you sure you want to delete this element?')) {
                    // }
                }
            });
    </script>
@endsection
