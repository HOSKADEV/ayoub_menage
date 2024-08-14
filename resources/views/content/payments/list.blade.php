@extends('layouts/contentNavbarLayout')

@section('title', __('Payments'))

@section('content')

    <h4 class="fw-bold py-3 mb-3">
        <span id="payable_name"> <span class="text-muted fw-light">{{ $payable->name }} /</span> {{ __('Payments') }}</span>
        <button type="button" class="btn btn-primary" id="create"
            style="float:right;margin-left:10px">{{ __('Add payment') }}</button>
        <button type="button" class="btn btn-primary" id="multi_pay" style="float:right"
            hidden>{{ __('Multi payments') }}</button>
        <button type="button" class="btn btn-primary" id="submit_multi_pay" style="float:right"
            hidden>{{ __('Pay payments') }}</button>
        <input type="text" class="form-control" id="payable_id" value="{{ $payable->id }}" hidden />
        <input type="text" class="form-control" id="payable_type" value="{{ get_class($payable) }}" hidden />
    </h4>

    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#" enctype="multipart/form-data"
        id="attachment_form">
        <input class="form-control" type="text" id="paymentId" name="payment_id" hidden>
        <input class="form-control" type="file" id="receipt" name="receipt" accept="image/*,.pdf,.doc,.docx" hidden>
    </form>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        {{-- <h5 class="card-header">{{ __('Payments table') }}</h5> --}}
            {{-- <select class="filter-select" id="type_filter">
                <option value=""> {{ __('Type filter') }}</option>
                <option value="1"> {{ __('Payments made') }}</option>
                <option value="2"> {{ __('Payments received') }}</option>
            </select> --}}

            <div class="row  justify-content-between">
              <div class="form-group col-md-3 p-3">
                <label for="type" class="form-label">{{ __('Paid filter') }}</label>
                <select class="form-select" id="paid_filter">
                    <option value=""> {{ __('Not selected') }}</option>
                    <option value="yes"> {{ __('Paid') }}</option>
                    <option value="no"> {{ __('Unpaid') }}</option>
                </select>
              </div>

              <div class="form-group col-md-3 p-3">
                <label for="type" class="form-label">{{ __('from date') }}</label>
                <input class="filter-select" id="start_date" type="text" size="14" placeholder="mm/dd/yyyy"
                  onfocus="(this.type='date')" onblur="(this.type='text')">
              </div>

              <div class="form-group col-md-3 p-3">
                <label for="type" class="form-label">{{ __('to date') }}</label>
                <input class="filter-select" id="end_date" type="text" size="14" placeholder="mm/dd/yyyy"
                    onfocus="(this.type='date')" onblur="(this.type='text')">
              </div>
            </div>

        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sum">{{ __('Amount') }}</th>
                       {{--  <th>{{ __('Type') }}</th> --}}
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Receipt') }}</th>
                        <th>{{ __('is Paid') }}</th>
                        <th>{{ __('Paid at') }}</th>
                        {{-- <th>{{ __('Payer name') }}</th>
                        <th>{{ __('Payee name') }}</th> --}}
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('Total') }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        {{-- <th></th>
                        <th></th>
                        <th></th> --}}
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- payment modal --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1">{{ __('Add payment') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="form_type" hidden />
                    <input type="text" class="form-control" id="id" name="id" hidden />
                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="form">

                        <div class="mb-3">
                            <label class="form-label" for="amount">{{ __('Amount') }}</label>
                            <input type="text" class="form-control" id="amount" name="amount" />
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="receipt">{{ __('Receipt') }}</label>
                          <input type="file" class="form-control" name="receipt" accept="image/*,.pdf,.doc,.docx"/>
                      </div>

                        {{-- <div class="mb-3">
                            <label class="form-label" for="payment_method">{{ __('Payment method') }}</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="cash"> {{ __('Cash') }}</option>
                                <option value="card"> {{ __('Card') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="is_paid">{{ __('Is paid') }}</label>
                          <select class="form-select" id="is_paid" name="is_paid">
                              <option value="yes"> {{ __('Yes') }}</option>
                              <option value="no"> {{ __('no') }}</option>
                          </select>
                      </div> --}}


                        <div class="mb-3" style="text-align: center">
                            <button type="submit" id="submit" name="submit"
                                class="btn btn-primary">{{ __('Send') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pay_modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1">{{ __('Pay payment') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="pay_form">


                        <input type="text" class="form-control" id="payment_id" name="payment_id" hidden />

                        <div class="mb-3">
                            <label class="form-label" for="payer_name">{{ __('Payer name') }}</label>
                            <input type="text" class="form-control" id="payer_name" name="payer_name" />
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="payee_name">{{ __('Payee name') }}</label>
                            <input type="text" class="form-control" id="payee_name" name="payee_name" />
                        </div>


                        <div class="mb-3" style="text-align: center">
                            <button type="submit" id="submit_pay" name="submit_pay"
                                class="btn btn-primary">{{ __('Send') }}</button>
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
            load_data();

            function load_data(is_paid = null, start_date = null, end_date = null, multi_pay = 0) {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var payable_id = document.getElementById('payable_id').value;
                var payable_type = document.getElementById('payable_type').value;
                var table = $('#laravel_datatable').DataTable({

                    dom: 'Bfrtip',

                    buttons: [
                        'pageLength',

                        {
                            extend: 'print',
                            download: 'open',
                            titleAttr: 'Print Table',
                            className: 'btn--secondary btn-md',
                            title: document.getElementById('payable_name').innerText,
                            footer: true,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 5, 6, 7, 8]

                            },
                        },



                    ],

                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 100,

                    ajax: {
                        url: "{{ url('payment/list') }}",
                        data: {
                            payable_id: payable_id,
                            payable_type: payable_type,
                            is_paid: is_paid,
                            start_date: start_date,
                            end_date: end_date,
                        },
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },

                    columns: [

                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            render: function (data, type, row, meta){
                                if (multi_pay == 0) {
                                    return data;
                                } else {
                                    return '<input class="form-check-input check" type="checkbox" value="" payment_id="' +
                                        row['id'] + '">';
                                }
                            },
                        },

                        {
                            data: 'amount',
                            name: 'amount'
                        },

                        /* {
                            data: 'type',
                            name: 'type',
                            render: function(data) {
                                if (data == 1) {
                                    return '<span class="badge bg-warning">{{ __('Payment made') }}</span>';
                                } else if (data == 2) {
                                    return '<span class="badge bg-info">{{ __('Payment received') }}</span>';
                                } else {
                                    return null;
                                }

                            }
                        }, */


                        {
                            data: 'created_at',
                            name: 'created_at'
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
                            data: 'is_paid',
                            name: 'is_paid',
                            render: function(data) {
                                if (data == 'no') {
                                    return '<span class="badge bg-danger">{{ __('No') }}</span>';
                                } else if (data == 'yes') {
                                    return '<span class="badge bg-success">{{ __('Yes') }}</span>';
                                } else {
                                    return null;
                                }

                            },

                        },



                        {
                            data: 'paid_at',
                            name: 'paid_at'
                        },


                        /* {
                            data: 'payer_name',
                            name: 'payer_name'
                        },

                        {
                            data: 'payee_name',
                            name: 'payee_name'
                        }, */


                        {
                            data: 'action',
                            name: 'action',
                            render: function(data) {
                                /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                                  +data+'</div></div>' */
                                return '<span>' + data + '</span>';
                            }
                        }

                    ],
                    footerCallback: function(row, data, start, end, display) {
                        let api = this.api();

                        // Remove the formatting to get integer data for summation
                        let intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i :
                                0;
                        };

                        api.columns('.sum', {
                            page: 'total'
                        }).every(function() {
                            var sum = this
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            this.footer().innerHTML = sum;
                        });
                    }
                });
            }

            function refresh_table(multi_pay = 0) {
                var paid = document.getElementById('paid_filter').value;
                var start_date = document.getElementById('start_date').value;
                var end_date = document.getElementById('end_date').value;
                var table = $('#laravel_datatable').DataTable();
                table.destroy();
                load_data(paid, start_date, end_date, multi_pay);
            }


            /* $('#type_filter').on('change', function() {
                refresh_table()
            }); */

            $('#paid_filter').on('change', function() {
              document.getElementById('submit_multi_pay').hidden = true;
                if (document.getElementById('paid_filter').value == 'no') {
                    document.getElementById('multi_pay').hidden = false;
                } else {
                    document.getElementById('multi_pay').hidden = true;
                }
                refresh_table()
            });

            $('#start_date').on('change', function() {
                refresh_table()
            });

            $('#end_date').on('change', function() {
                refresh_table()
            });

            $('#multi_pay').on('click', function() {
                refresh_table(1);
                document.getElementById('multi_pay').hidden = true;
                document.getElementById('submit_multi_pay').hidden = false;
            });

            $('#create').on('click', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "create";
                $("#modal").modal('show');
            });


            $(document.body).on('click', '.pay', function() {
                /* document.getElementById('pay_form').reset();
                var payment_id = $(this).attr('table_id');
                $("#payment_id").val(payment_id);
                $("#pay_modal").modal('show'); */

                var payment_id = $(this).attr('table_id');

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
                            url: "{{ url('payment/update') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                payment_id: payment_id,
                                is_paid : 'yes'
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



            $('#submit').on('click', function() {

                var formdata = new FormData($("#form")[0]);
                var formtype = document.getElementById('form_type').value;
                console.log(formtype);
                if (formtype == "create") {
                    url = "{{ url('payment/create') }}";
                    formdata.append("payable_id", document.getElementById('payable_id').value)
                    formdata.append("payable_type", document.getElementById('payable_type').value)
                }

                if (formtype == "update") {
                    url = "{{ url('payment/update') }}";
                    formdata.append("payable_id", document.getElementById('payable_id').value)
                    formdata.append("payable_type", document.getElementById('payable_type').value)
                }

                $("#modal").modal("hide");


                $.ajax({
                    url: url,
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


            $('#submit_pay').on('click', function() {

                var formdata = new FormData($("#pay_form")[0]);
                formdata.append('is_paid', 1);

                $("#pay_modal").modal("hide");


                $.ajax({
                    url: "{{ url('payment/update') }}",
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

            $('#submit_multi_pay').on('click', function() {

              Swal.fire({
                    title: "{{ __('Warning') }}",
                    text: "{{ __('Are you sure?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {

                  var formdata = new FormData();

                  var payable_id = document.getElementById('payable_id').value;

                  formdata.append('payable_id',payable_id);

                  var checkboxes = document.getElementsByClassName('check');



                  var k = 0;
                  for (var i=0; i<checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                      formdata.append(`payments[${k}]` , checkboxes[i].getAttribute('payment_id') );
                      k = k+1;
                    }
                  }


                  $.ajax({
                      url: "{{ url('payment/multi_pay') }}",
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
            });

            $('#receipt').on('change', function() {

                var formdata = new FormData($("#attachment_form")[0]);

                $.ajax({
                    url: "{{ url('payment/update') }}",
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

                document.getElementById('paymentId').value = null;

            });

            $(document.body).on('click', '.attach', function() {

                var payment_id = $(this).attr('table_id');
                document.getElementById('paymentId').value = payment_id;
                document.getElementById('receipt').click();

            });

            $(document.body).on('click', '.delete', function() {

                var payment_id = $(this).attr('table_id');

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
                            url: "{{ url('payment/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                payment_id: payment_id
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


        });
    </script>
@endsection
