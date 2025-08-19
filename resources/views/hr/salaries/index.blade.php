@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Salary Management</h1>
        <button onclick="document.getElementById('addSalaryModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Process Salary
        </button>
    </div>

    <!-- Salary Records Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Allowances</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salaries as $salary)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-600">{{ substr($salary->user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $salary->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $salary->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($salary->created_at)->format('M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($salary->basic_salary, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format(collect(json_decode($salary->allowances))->sum('amount'), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format(collect(json_decode($salary->deductions))->sum('amount'), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ number_format($salary->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Payslip</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No salary records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $salaries->links() }}
        </div>
    </div>
</div>

<!-- Add Salary Modal -->
<div id="addSalaryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Process New Salary</h3>
            <button onclick="document.getElementById('addSalaryModal').classList.add('hidden')" 
                    class="text-gray-500 hover:text-gray-700">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        
        <form action="{{ route('hr.salaries.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-medium mb-4">Employee Information</h4>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Staff Member</label>
                        <select name="user_id" required 
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Staff</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Basic Salary</label>
                        <input type="number" name="basic_salary" step="0.01" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" required 
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium">Allowances</h4>
                        <button type="button" onclick="addAllowanceField()" 
                                class="text-sm text-blue-600 hover:text-blue-800">
                            + Add Allowance
                        </button>
                    </div>
                    <div id="allowancesContainer" class="space-y-2 mb-6">
                        <!-- Dynamic allowance fields will be added here -->
                    </div>
                    
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium">Deductions</h4>
                        <button type="button" onclick="addDeductionField()" 
                                class="text-sm text-red-600 hover:text-red-800">
                            + Add Deduction
                        </button>
                    </div>
                    <div id="deductionsContainer" class="space-y-2">
                        <!-- Dynamic deduction fields will be added here -->
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Any additional notes..."></textarea>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('addSalaryModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Process Salary
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Add allowance field
    function addAllowanceField() {
        const container = document.getElementById('allowancesContainer');
        const index = container.children.length;
        
        const div = document.createElement('div');
        div.className = 'flex space-x-2';
        div.innerHTML = `
            <input type="text" name="allowances[${index}][name]" 
                   placeholder="Allowance name" required
                   class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <input type="number" name="allowances[${index}][amount]" 
                   placeholder="Amount" step="0.01" required
                   class="w-32 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-500 hover:text-red-700">
                ×
            </button>
        `;
        container.appendChild(div);
    }
    
    // Add deduction field
    function addDeductionField() {
        const container = document.getElementById('deductionsContainer');
        const index = container.children.length;
        
        const div = document.createElement('div');
        div.className = 'flex space-x-2';
        div.innerHTML = `
            <input type="text" name="deductions[${index}][name]" 
                   placeholder="Deduction name" required
                   class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            <input type="number" name="deductions[${index}][amount]" 
                   placeholder="Amount" step="0.01" required
                   class="w-32 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-500 hover:text-red-700">
                ×
            </button>
        `;
        container.appendChild(div);
    }
    
    // Add default allowance and deduction fields when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Add default allowance
        addAllowanceField();
        
        // Add default deduction
        addDeductionField();
    });
</script>
@endpush
@endsection
