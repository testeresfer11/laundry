<script>
    // Order Cancelled
    $(document).on('click', '.reject', function() {
        var id = $(this).data('id');
        var value = $(this).data('value');
        Swal.fire({
            title: "Are you sure?",
            text: `Do you want to ${value} the order?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: `Yes, ${value}`
        }).then((result) => {
            if (result.isConfirmed) {
                $('#order_id').val(id);
                
                if(value =='Reject'){
                    $('#order_status').val('Rejected');
                    $('#image_input').show();
                }else{
                    $('#order_status').val('Cancelled');
                    $('#image_input').hide();
                }
                $('#orderReject').modal('show');
                $('#orderReject .order-title').text(`Order ${value}`);
            } 
        });
    });

    //Order Cancelled form validation
    // Only initialize validation once
    if (!$('#order_reject_form').data('validator')) {
        $('#order_reject_form').validate({
            rules: {
                reason: {
                    required: true,
                    noSpace: true,
                    minlength: 3,
                    maxlength: 200,
                },
                image:  {
                    extension: "jpeg,jpg,png,gif",
                }
            },
            messages: {
                reason: {
                    required: "Reason is required.",
                    minlength: "Reason must consist of at least 3 characters.",
                    maxlength: "Reason must not be greater than 200 characters."
                },
                image:  {
                    extension: "Please upload a valid image file."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr('name') === 'reason') {
                    error.insertAfter('#reject_reason');
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.api_response === 'success') {
                            $('#orderReject').modal('hide');
                            // if ($('.reject').data('title') === 'dashboard-reject') {
                            //     setTimeout(() => window.location = response.data, 1000);
                            // } else {
                                 setTimeout(() => location.reload(), 1000);
                            // }
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        $('.error').text('');
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    $('#' + key + '-error').text(errors[key][0]);
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    // Assign Driver
    $("#assign_driver_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            order_id: {
                required: true,
            },
            driver_id: {
                required: true,
            },
        },
        messages: {
            order_id: {
                required: "Order id is required",
            },
            driver_id: {
                required: "Please select driver.",
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr('name') === 'driver_id') {
                error.insertAfter('.row');
            } 
        },
        submitHandler: function(form) {     
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: form.method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.api_response == 'success'){
                        $('#assign_driver').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('.error').text('');

                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#' + key + '-error').text(errors[key][0]);
                            }
                        }
                    }
                }
            });
        }
    });
    

    $(document).on('change', '.qty'  ,function() {
        var row = $(this).closest('tr');
        var price = row.find('.price').data('value');
        var qty = row.find('.qty').val();
        row.find('.amount').html(price * qty);
    });

    $(document).on('click', '.remove_service', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to remove the service?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Remove"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: "/order/service/remove/"+id,
                type: "GET", 
                success: function(response) {
                    if(response.api_response == 'success'){
                        if(response.data.count == 1){
                            Swal.fire({
                                title: "OOPs! Unable to delete. ",
                                text: response.message,
                                icon: "info",
                                confirmButtonColor: "#2ea57c",
                                confirmButtonText: "Ok"
                            });
                        } else {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                            toastr.success(response.message);

                        }
                    }else{
                        toastr.error(response.message);
                    }
                },
            });
            } 
        });
    });

    $(document).on('click', '.update_service', function() {
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        var price = row.find('.price').data('value');
        var qty = row.find('.qty').val();
        amount = price * qty;
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to update the service?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Update"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: `/order/service/update/${id}/${amount}/${qty}`,
                type: "GET", 
                success: function(response) {
                    if(response.api_response == 'success'){
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                },
            });
            } 
        });
    });
</script>