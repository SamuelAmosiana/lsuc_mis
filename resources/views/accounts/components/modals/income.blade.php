<!-- Record Income Modal -->
<div class="modal fade" id="recordIncomeModal" tabindex="-1" role="dialog" aria-labelledby="recordIncomeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordIncomeModalLabel">Record Income</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('accounts.income.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="incomeCategory">Category</label>
                        <select class="form-control" id="incomeCategory" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach(\App\Models\FinancialCategory::where('type', 'income')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="incomeAmount">Amount (M)</label>
                        <input type="number" step="0.01" class="form-control" id="incomeAmount" name="amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="incomeSource">Source</label>
                        <input type="text" class="form-control" id="incomeSource" name="source" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="incomeDate">Date Received</label>
                        <input type="date" class="form-control" id="incomeDate" name="date_received" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="incomePaymentMethod">Payment Method</label>
                        <select class="form-control" id="incomePaymentMethod" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="incomeDescription">Description</label>
                        <textarea class="form-control" id="incomeDescription" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Income</button>
                </div>
            </form>
        </div>
    </div>
</div>
