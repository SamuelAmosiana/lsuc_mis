<!-- Recent Transactions -->
<div class="col-lg-8 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
            <a href="{{ route('accounts.reports.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->entry_date->format('M d, Y') }}</td>
                            <td>{{ Str::limit($transaction->description, 40) }}</td>
                            <td>
                                <span class="badge {{ $transaction->credit > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $transaction->credit > 0 ? 'Income' : 'Expense' }}
                                </span>
                            </td>
                            <td class="text-{{ $transaction->credit > 0 ? 'success' : 'danger' }}">
                                {{ $transaction->credit > 0 ? '+' : '-' }} M{{ number_format($transaction->credit > 0 ? $transaction->credit : $transaction->debit, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No recent transactions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
