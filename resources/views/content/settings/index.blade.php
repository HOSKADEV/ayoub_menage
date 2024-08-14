@extends('layouts/contentNavbarLayout')

@section('title', __('Sections'))

@section('content')

    <div id="loading-page">
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Settings') }}
        </h4>

        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#" enctype="multipart/form-data"
            id="setting_form">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('Defaults') }}</h5>
                            <small class="text-muted float-end">{{ __('Defaults') }}</small>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <input hidden type="text" id="setting_id" name="setting_id" value="{{ $settings->id }}">

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="default_category">{{ __('Default category') }}</label>
                                    <select class="form-select" id="default_category" name="default_category">
                                        <option value="" @if (empty($settings->default_category)) selected @endif>
                                            {{ __('Not selected') }} </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if ($settings->default_category == $category->id) selected @endif>{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label"
                                        for="default_subcategory">{{ __('Default subcategory') }}</label>
                                    <select class="form-select" id="default_subcategory" name="default_subcategory">
                                        <option value="" @if (empty($settings->default_subcategory)) selected @endif>
                                            {{ __('Not selected') }} </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('Price Maximum') }}</h5>
                            <small class="text-muted float-end">{{ __('Price Maximum') }}</small>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                {{-- <input hidden type="text" id="setting_id" name="setting_id" value="{{ $settings->id}}"> --}}

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="price_max">{{ __('Price Maximum') }}</label>
                                    <input type="text" class="form-control" id="price_max" name="price_max"
                                        placeholder="{{ __('Price Maximum') }}" value="{{ $settings->price_max }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('Bank account number') }}</h5>
                            <small class="text-muted float-end">{{ __('Bank account number') }}</small>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"
                                                for="bank_account_bankily">{{ __('Bankily account number') }} </label>
                                            <input type="text" class="form-control" id="bank_account_bankily"
                                                name="bank_account_bankily"
                                                placeholder="{{ __('Bankily account number') }}"
                                                value="{{ $settings->bank_account_bankily }}" />
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"
                                                for="bank_account_sedad">{{ __('Sedad account number') }} </label>
                                            <input type="text" class="form-control" id="bank_account_sedad"
                                                name="bank_account_sedad" placeholder="{{ __('Sedad account number') }}"
                                                value="{{ $settings->bank_account_sedad }}" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"
                                                for="bank_account_bimbank">{{ __('Bimbank Mobile account number') }}
                                            </label>
                                            <input type="text" class="form-control" id="bank_account_bimbank"
                                                name="bank_account_bimbank"
                                                placeholder="{{ __('Bimbank Mobile account number') }}"
                                                value="{{ $settings->bank_account_bimbank }}" />
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"
                                                for="bank_account_masrfy">{{ __('Masrify account number') }} </label>
                                            <input type="text" class="form-control" id="bank_account_masrfy"
                                                name="bank_account_masrfy" placeholder="{{ __('Masrify account number') }}"
                                                value="{{ $settings->bank_account_masrfy }}" />
                                        </div>
                                    </div>

                                    <div class="mb-3" style="text-align: center">
                                        <button type="submit" id="submit" name="submit"
                                            class="btn btn-primary update">{{ __('Send') }}</button>
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
                    type: 'POST',
                    data: queryString,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire(
                                "{{ __('Success') }}",
                                "{{ __('success') }}",
                                'success'
                            ).then((result) => {
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

            $('#default_category').on('change', function(e, callback) {
                var category_id = document.getElementById('default_category').value;
                $.when(
                    $.ajax({
                        url: '{{ url('subcategory/get?all=1') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            category_id: category_id
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.status == 1) {

                                var subcategories = document.getElementById(
                                    'default_subcategory');
                                subcategories.innerHTML =
                                    '<option value="">{{ __('Not selected') }}</option>';

                                for (var i = 0; i < response.data.length; i++) {
                                    var option = document.createElement('option');
                                    option.value = response.data[i].id;
                                    option.innerHTML = response.data[i].name;
                                    subcategories.appendChild(option);
                                }

                            }
                        }
                    })
                ).done(function(a1, a2) {
                    callback();
                });



            });

            $('#default_category').trigger("change", function() {
                document.getElementById('default_subcategory').value =
                    '{{ $settings->default_subcategory }}';
            });
        });
    </script>
@endsection
