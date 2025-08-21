document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const recordPaymentModal = new bootstrap.Modal(document.getElementById('recordPaymentModal'));
    const paymentHistoryModal = new bootstrap.Modal(document.getElementById('paymentHistoryModal'));
    
    // Record Payment Modal Setup
    document.querySelectorAll('.record-payment').forEach(button => {
        button.addEventListener('click', function() {
            const feeId = this.dataset.feeId;
            const student = this.dataset.student;
            const feeType = this.dataset.feeType;
            const balance = parseFloat(this.dataset.balance);
            
            document.getElementById('studentName').value = student;
            document.getElementById('feeType').value = feeType;
            document.getElementById('outstandingBalance').value = balance.toFixed(2);
            
            const amountInput = document.getElementById('amount');
            amountInput.value = balance.toFixed(2);
            amountInput.max = balance;
            
            const form = document.getElementById('paymentForm');
            form.action = `/accounts/fees/${feeId}/payments`;
            
            recordPaymentModal.show();
        });
    });
    
    // View Payment History
    document.querySelectorAll('.view-payments').forEach(button => {
        button.addEventListener('click', function() {
            const feeId = this.dataset.feeId;
            
            fetch(`/accounts/fees/${feeId}/payments/history`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('paymentHistoryContent').innerHTML = data.html;
                        paymentHistoryModal.show();
                    } else {
                        alert('Failed to load payment history');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading payment history');
                });
        });
    });
    
    // Amount validation
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const max = parseFloat(this.max) || 0;
            const value = parseFloat(this.value) || 0;
            
            if (value > max) {
                this.value = max.toFixed(2);
            } else if (value < 0) {
                this.value = '0.00';
            }
        });
    }
    
    // Form submission handling
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const balance = parseFloat(document.getElementById('outstandingBalance').value) || 0;
            
            if (amount <= 0) {
                e.preventDefault();
                alert('Please enter a valid payment amount');
                return false;
            }
            
            if (amount > balance) {
                e.preventDefault();
                alert('Payment amount cannot exceed the outstanding balance');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            
            // Reset button state if form submission fails
            window.addEventListener('unload', function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
            
            return true;
        });
    }
});
