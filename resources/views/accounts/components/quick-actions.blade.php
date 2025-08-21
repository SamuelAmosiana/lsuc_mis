<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-success btn-icon-split w-100" data-bs-toggle="modal" data-bs-target="#recordIncomeModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Record Income</span>
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-danger btn-icon-split w-100" data-bs-toggle="modal" data-bs-target="#recordExpenseModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-minus"></i>
                            </span>
                            <span class="text">Record Expense</span>
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounts.fees.index') }}" class="btn btn-primary btn-icon-split w-100">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            <span class="text">Manage Fees</span>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounts.reports.index') }}" class="btn btn-info btn-icon-split w-100">
                            <span class="icon text-white-50">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <span class="text">View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
