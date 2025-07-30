@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Loan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('loans.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrower_type">Borrower Type</label>
                                    <select name="borrower_type" id="borrower_type" class="form-control" required>
                                        <option value="">Select Borrower Type</option>
                                        <option value="customer" {{ old('borrower_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="employee" {{ old('borrower_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrower_id">Borrower</label>
                                    <select name="borrower_id" id="borrower_id" class="form-control @error('loanable_id') is-invalid @enderror" required>
                                        <option value="">Select Borrower</option>
                                    </select>
                                    @error('loanable_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="loanable_type" id="loanable_type">
                        <input type="hidden" name="loanable_id" id="loanable_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Loan Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interest_rate">Interest Rate (%)</label>
                                    <input type="number" name="interest_rate" id="interest_rate" class="form-control @error('interest_rate') is-invalid @enderror" value="{{ old('interest_rate') }}" step="0.01" min="0" required>
                                    @error('interest_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loan_date">Loan Date</label>
                                    <input type="date" name="loan_date" id="loan_date" class="form-control @error('loan_date') is-invalid @enderror" value="{{ old('loan_date', date('Y-m-d')) }}" required>
                                    @error('loan_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Loan</button>
                            <a href="{{ route('loans.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const customers = @json($customers);
const employees = @json($employees);

document.getElementById('borrower_type').addEventListener('change', function() {
    const type = this.value;
    const borrowerSelect = document.getElementById('borrower_id');
    const loanableType = document.getElementById('loanable_type');
    const loanableId = document.getElementById('loanable_id');
    
    borrowerSelect.innerHTML = '<option value="">Select Borrower</option>';
    
    if (type === 'customer') {
        loanableType.value = 'App\\Models\\Customer';
        customers.forEach(customer => {
            borrowerSelect.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
        });
    } else if (type === 'employee') {
        loanableType.value = 'App\\Models\\Employee';
        employees.forEach(employee => {
            borrowerSelect.innerHTML += `<option value="${employee.id}">${employee.name}</option>`;
        });
    }
});

document.getElementById('borrower_id').addEventListener('change', function() {
    document.getElementById('loanable_id').value = this.value;
});
</script>
@endsection
