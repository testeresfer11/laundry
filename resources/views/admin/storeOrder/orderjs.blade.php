@section('scripts')
<script>
    $('#transaction_id_group').hide();
    $('input[name="payment_method"]').change(function() {
        if ($(this).val() === 'card') {
            $('#transaction_id_group').show();
        } else {
            $('#transaction_id_group').hide();
        }
    });

    $('.PaidOrder').on('click', function() {
        var id = $(this).attr('data-id');
        $('input[name="order_id"]').val(id)
        Swal.fire({
            title: "Are you sure?",
            text: "You want to mark order as paid?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2ea57c",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Paid !"
            }).then((result) => {
            if (result.isConfirmed) {
                $('#orderPaid').modal('show');
            }
        });
    });

    $("#order_paid_form").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            payment_method: {
                required: true,
            },
            transaction_id: {
                required: {
                    depends: function(element) {
                        return $('input[name="payment_method"]:checked').val() === 'card';
                    }
                }
            },
        },
        messages: {
            payment_method: {
                required: "Please select the payment method.",
            },
            transaction_id: {
                required: "Transaction ID is required when paying by card.",
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr('name') === 'transaction_id') {
                error.insertAfter('#transaction_id');
            } else {
                error.insertAfter('.form-group');
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
                        $('#orderPaid').modal('hide');
                        setTimeout(() => {
                            // window.location = response.data;
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

</script>
@stop