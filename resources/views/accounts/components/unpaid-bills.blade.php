<!-- Unpaid Bills -->
<div class="col-lg-4 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">Unpaid Bills</h6>
            <a href="{{ route('accounts.fees.index') }}" class="btn btn-sm btn-warning">View All</a>
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($unpaidBills as $bill)
                <a href="{{ route('accounts.fees.show', $bill->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $bill->student->name ?? 'N/A' }}</h6>
                        <small class="text-danger">Due: {{ $bill->due_date->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">Bill #{{ $bill->bill_number }}</p>
                    <small>Amount: M{{ number_format($bill->total_amount - $bill->amount_paid, 2) }} remaining</small>
                </a>
                @empty
                <div class="text-center p-3">
                    <p class="mb-0">No unpaid bills</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
