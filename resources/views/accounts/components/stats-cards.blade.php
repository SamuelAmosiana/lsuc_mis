<!-- Stats Cards -->
<div class="row">
    <!-- Total Income -->
    <div class="col-md-3 mb-4">
        <div class="stat-card card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Income</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($totalIncome, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Expenses -->
    <div class="col-md-3 mb-4">
        <div class="stat-card card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Expenses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($totalExpenses, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Receivable -->
    <div class="col-md-3 mb-4">
        <div class="stat-card card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Receivable</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($totalReceivable, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Balance -->
    <div class="col-md-3 mb-4">
        <div class="stat-card card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Cash Balance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($cashBalance, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
