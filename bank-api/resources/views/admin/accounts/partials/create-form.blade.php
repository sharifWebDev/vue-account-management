<form method="POST" id="createaccountsForm" action="{{ url('api/v1/accounts') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="account_name">Account Name</label>
        <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name') }}" placeholder="Enter Account Name..." id="create_account_name" required>
        @error('account_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="account_number">Account Number</label>
        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" placeholder="Enter Account Number..." id="create_account_number" required>
        @error('account_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="company_id">Company Id</label>
        <input type="number" name="company_id" min="0" class="form-control @error('company_id') is-invalid @enderror" value="{{ old('company_id') }}" placeholder="Enter Company Id..." id="create_company_id" required>
        @error('company_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="bank_id">Bank Id</label>
        <input type="number" name="bank_id" min="0" class="form-control @error('bank_id') is-invalid @enderror" value="{{ old('bank_id') }}" placeholder="Enter Bank Id..." id="create_bank_id" required>
        @error('bank_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="branch_id">Branch Id</label>
        <input type="number" name="branch_id" min="0" class="form-control @error('branch_id') is-invalid @enderror" value="{{ old('branch_id') }}" placeholder="Enter Branch Id..." id="create_branch_id" required>
        @error('branch_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="cheque_book">Cheque Book</label>
        <textarea name="cheque_book" class="form-control @error('cheque_book') is-invalid @enderror" placeholder="Enter Cheque Book..." id="create_cheque_book" required>{{ old('cheque_book') }}</textarea>
        @error('cheque_book')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="opening_balance">Opening Balance</label>
        <input type="number" step="any" name="opening_balance" min="0" class="form-control @error('opening_balance') is-invalid @enderror" value="{{ old('opening_balance') }}" placeholder="Enter Opening Balance..." id="create_opening_balance" required>
        @error('opening_balance')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="created">Created</label>
        <input type="datetime-local" name="created" class="form-control @error('created') is-invalid @enderror" value="{{ old('created') }}" id="create_created" required>
        @error('created')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="modified">Modified</label>
        <input type="datetime-local" name="modified" class="form-control @error('modified') is-invalid @enderror" value="{{ old('modified') }}" id="create_modified" required>
        @error('modified')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="status">Status</label>
        <input type="radio" name="status" id="create_status_yes" value="1" {{ old('status') == 1 ? "checked" : "" }} checked> 
                            <label for="create_status_yes">Status Yes</label>
<input type="radio" name="status" id="create_status_no" value="0" {{ old('status') == 0 ? "checked" : "" }}> 
                            <label for="create_status_no">Status No</label>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
        <div class="mb-3 text-right">
            <a type="button" class="btn bg-danger text-white" href="{{ route('admin.accounts.index') }}">Cancel</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>