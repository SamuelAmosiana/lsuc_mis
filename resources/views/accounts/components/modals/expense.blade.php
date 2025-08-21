<!-- Record Expense Modal -->
<div class="modal fade" id="recordExpenseModal" tabindex="-1" role="dialog" aria-labelledby="recordExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordExpenseModalLabel">Record Expense</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('accounts.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="expenseCategory">Category</label>
                        <select class="form-control" id="expenseCategory" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach(\App\Models\FinancialCategory::where('type', 'expense')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expenseAmount">Amount (M)</label>
                        <input type="number" step="0.01" class="form-control" id="expenseAmount" name="amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expensePayee">Payee</label>
                        <input type="text" class="form-control" id="expensePayee" name="payee" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expenseDate">Date Paid</label>
                        <input type="date" class="form-control" id="expenseDate" name="date_paid" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expensePaymentMethod">Payment Method</label>
                        <select class="form-control" id="expensePaymentMethod" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expenseReference">Reference Number (Optional)</label>
                        <input type="text" class="form-control" id="expenseReference" name="reference_number">
                    </div>
                    <div class="form-group">
                        <label for="expenseDescription">Description</label>
                        <textarea class="form-control" id="expenseDescription" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Record Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
