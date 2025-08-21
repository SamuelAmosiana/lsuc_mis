<div class="modal-header">
    <h5 class="modal-title">Payment History - {{ $fee->feeType->name ?? 'Fee' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Student:</strong> {{ $fee->bill->student->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Student ID:</strong> {{ $fee->bill->student->student_id ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-1"><strong>Fee Amount:</strong> M{{ number_format($fee->amount, 2) }}</p>
                <p class="mb-1"><strong>Discount:</strong> M{{ number_format($fee->discount, 2) }}</p>
                <p class="mb-1"><strong>Amount Paid:</strong> M{{ number_format($fee->amount_paid, 2) }}</p>
                <p class="mb-1"><strong>Balance:</strong> M{{ number_format($fee->amount - $fee->amount_paid - $fee->discount, 2) }}</p>
            </div>
        </div>
    </div>
    
    @if($fee->payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Recorded By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fee->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>M{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                        <td>{{ $payment->recordedBy->name ?? 'System' }}</td>
                        <td>{{ $payment->notes ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total Paid:</th>
                        <th>M{{ number_format($fee->payments->sum('amount'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No payment history found for this fee.
        </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
