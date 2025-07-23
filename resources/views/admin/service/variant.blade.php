@extends('admin.layouts.app')
@section('title', 'Add Service')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Variant</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Product Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.service.list')}}">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page">Variants</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
<div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Variant</h4>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="view-user-details">
                                <div class="text-center">
                                    <img class="service-image"
                                        @if (isset($service->image) && !is_null($service->image)) src="{{ asset('storage/images/' . $service->image) }}"
                            @else
                                src="{{ asset('admin/images/faces/face15.jpg') }}" @endif
                                        onerror="this.src = '{{ asset('admin/images/faces/face15.jpg') }}'"
                                        alt="User profile picture">
                                </div>
                            </div>
    
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="response-data ml-4">
                                <h6 class="f-14 mb-1">
                                    <span class="semi-bold qury">Service Name : </span> 
                                    <span class="text-muted" >{{ $service->name }}</span>
                                </h6>
                                <h6 class="f-14 mb-1">
                                    <span class="semi-bold qury">Description: </span> 
                                    <span class="text-muted" >{{ $service->description }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: end;">
                        <button class="btn btn-primary mt-4 service_variant" data-bs-toggle="modal" data-bs-target="#add_service_variant">Add Variant</button>
                    </div>
                     <!-- Edit Variant modal start -->
                     <div class="modal fade" id="add_service_variant" tabindex="-1" aria-labelledby="add_service_variantLabel"aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fs-5" id="add_service_variantLabel">Add Service Variant</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                </div>
                                <div class="modal-body">
                                <form action="{{route('admin.service.addVariant')}}" method="POST" id="add_service_variant_form">
                                    @csrf
                                    <input type="hidden" name="service_id" value="{{$service->id}}">
                                        <div class="mt-3 checklist-table checklist-table-list">
                                            @foreach (getCommonList('variant') as $key => $value)
                                                <div class="p-2 rounded checkbox-form mb-2">
                                                    <label class="checkbox" for="{{$key}}">
                                                    <input type="checkbox" class="variant_id"  value="{{$value}}" id="{{$key}}">
                                                    <span class="checkmark"></span>{{$value}}
                                                    </label>
                                                </div>
                                            @endforeach 
                                        </div>
                                    <button type="submit" class="btn btn-primary add_variant_button" >Add Variant</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Variant modal end -->
                    <form class="forms-sample" id="add-variant" action="{{route('admin.service.variant',['id'=> $service->id])}}" method="POST" >
                    @csrf
                        <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th> Variant Name </th>
                              <th> Variant Price </th>
                              <th> Action </th>
                            </tr>
                          </thead>
                          <tbody  id="variant_list">
                            @forelse ($service->serviceVariant as $value)
                                <tr data-id="{{$value->id}}">
                                    <td>
                                        <input type="hidden" name="variant_id[]" value="{{$value->variant_id}}">
                                        {{$value->variant ? $value->variant->name : ''}}
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="number" name="variant_price[]" value="{{$value->price}}" min="1"  max="1000"  class="form-control" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="button" class="removeVariant" value="Remove">
                                    </td>
                                </tr>
                            @empty
                                <tr class="no_record" style="text-align: center">
                                    <td colspan="3" > No record found</td>
                                </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    @if($service->serviceVariant()->count() == 0)
                    <button type="submit" class="btn btn-primary mr-2 mt-4" id="saveButton" style="display: none;">Save</button>
                    @else
                    <button type="submit" class="btn btn-primary mr-2 mt-4" id="saveButton">Save</button>
                    @endif

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>    
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        var variant_ids = [];

        // Get the variant list for the service when the "Add Variant" button is clicked
        $(document).on('click', '.service_variant', function() {
            $('.checklist-table-list').empty();
            service_id = $('input[name=service_id]').val();
            $.ajax({
                url: `/admin/service/variantList/${service_id}`,
                type: "GET",
                success: function(response) {
                    if (response.api_response == "success") {
                        $('.checklist-table-list').append(response.data);
                    }
                }
            });
        });

        // Create an Array for the selected fields
        $(document).on('click', '.variant_id', function() {
            variant_id = $(this).val();
            if ($(this).prop('checked')) {
                variant_ids.push(variant_id);
            } else {
                var index = variant_ids.indexOf(variant_id);
                if (index > -1) {
                    variant_ids.splice(index, 1);
                }
            }
        });

        /** Add multiple variants without submitting the form yet **/
        $('#add_service_variant_form').validate({
            submitHandler: function(form) {
                // Prevent form submission if no variants are selected
                if (variant_ids.length === 0) {
                    toastr.error('Please select variants');
                    return false; // Prevent form submission if no variants are selected
                }

                // Dynamically add selected variants to the table
                variant_ids.forEach(function(variantId) {
                    let newRow = `
                        <tr data-id="${variantId}">
                            <td>
                                <input type="hidden" name="variant_id[]" value="${variantId}">
                                ${variantId} <!-- You can replace this with the variant name if needed -->
                            </td>
                            <td>
                                <input type="number" name="variant_price[]" value="" min="1" max="1000" class="form-control" required>
                            </td>
                            <td>
                                <input type="button" class="removeVariant" value="Remove">
                            </td>
                        </tr>`;
                    $('#variant_list').append(newRow);
                });

                // Hide the modal
                $('#add_service_variant').modal('hide');
                $('#add_service_variant_form')[0].reset();
                variant_ids = []; // Clear selected variant IDs
                $('.no_record').remove();
                $('#saveButton').show(); // Show the Save button now
            }
        });

        /** Remove Variant **/
        $(document).on('click', '.removeVariant', function() {
            var checkbox = $(this).closest('tr');
            id = checkbox.data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to remove the variant from service?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2ea57c",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/service/removeVariant/${id}`,
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.api_response == "success") {
                                selector = 'tr[data-id="' + id + '"]';
                                $(selector).empty();
                                if (response.data == 0) {
                                    $('#variant_list').html(`<tr class="no_record" style="text-align: center">
                                    <td colspan="3"> No record found</td>
                                </tr>`);
                                    $('#saveButton').hide();
                                }
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        });

        /** Handle the Save Button to Submit the Form **/
        $('#saveButton').on('click', function() {
            // Check if prices are filled before submitting
            let isValid = true;
            $('input[name="variant_price[]"]').each(function() {
                if ($(this).val() === '' || parseFloat($(this).val()) <= 0) {
                    isValid = false;
                    return false; // Exit the loop
                }
            });

            if (isValid) {
                $('#add-variant').submit(); // Only submit the form if all variant prices are filled
            } else {
                toastr.error('Please enter valid prices for all variants.');
            }
        });
    });
</script>

@stop