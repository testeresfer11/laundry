 <!-- Order paid modal start -->
 <div class="modal fade" id="orderPaid" tabindex="-1" aria-labelledby="orderPaidLabel"aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Order Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.storeOrder.paid') }}" method="POST" id="order_paid_form">
                    @csrf
                    <input type="hidden" name="order_id" value="">
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                                    <label class="form-check-label" for="payment_cash">
                                        By Cash
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                    <label class="form-check-label" for="payment_card">
                                        By Card
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3" id="transaction_id_group" >
                        <label class="form-label font-weight-bold">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" id="transaction_id">
                        <span id="transaction_id-error" class="text-danger"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--  Order paid modal end -->