
            <form method="POST" id="editaccountsForm" action="{{ url('api/v1/accounts/update/' . request()->id ?? "") }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="account_name">Account Name</label><br>
                        <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name', $query->account_name ?? "") }}" placeholder="Enter Account Name..." id="edit_account_name" required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="account_number">Account Number</label><br>
                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $query->account_number ?? "") }}" placeholder="Enter Account Number..." id="edit_account_number" required>
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="company_id">Company Id</label><br>
                        <input type="number" name="company_id" min="0" class="form-control @error('company_id') is-invalid @enderror" value="{{ old('company_id', $query->company_id ?? "") }}" placeholder="Enter Company Id..." id="edit_company_id" required>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="bank_id">Bank Id</label><br>
                        <input type="number" name="bank_id" min="0" class="form-control @error('bank_id') is-invalid @enderror" value="{{ old('bank_id', $query->bank_id ?? "") }}" placeholder="Enter Bank Id..." id="edit_bank_id" required>
                        @error('bank_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="branch_id">Branch Id</label><br>
                        <input type="number" name="branch_id" min="0" class="form-control @error('branch_id') is-invalid @enderror" value="{{ old('branch_id', $query->branch_id ?? "") }}" placeholder="Enter Branch Id..." id="edit_branch_id" required>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="cheque_book">Cheque Book</label><br>
                        <textarea name="cheque_book" class="form-control @error('cheque_book') is-invalid @enderror" placeholder="Enter Cheque Book..." id="edit_cheque_book" required>{{ old('cheque_book', $query->cheque_book ?? "") }}</textarea>
                        @error('cheque_book')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="opening_balance">Opening Balance</label><br>
                        <input type="number" step="any" name="opening_balance" min="0" class="form-control @error('opening_balance') is-invalid @enderror" value="{{ old('opening_balance', $query->opening_balance ?? "") }}" placeholder="Enter Opening Balance..." id="edit_opening_balance" required>
                        @error('opening_balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="created">Created</label><br>
                        <input type="datetime-local" name="created" class="form-control @error('created') is-invalid @enderror" value="{{ old('created', $query->created ?? "") }}" id="edit_created" required>
                        @error('created')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="modified">Modified</label><br>
                        <input type="datetime-local" name="modified" class="form-control @error('modified') is-invalid @enderror" value="{{ old('modified', $query->modified ?? "") }}" id="edit_modified" required>
                        @error('modified')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="status">Status</label><br>
                        
                        <input type="radio" name="status" value="1" {{ old('status', $query->status ?? "") == 1 ? "checked" : "" }} id="edit_status_yes" checked>
                        <label for="edit_status_yes">Status Yes</label>
                        <input type="radio" name="status" value="0" {{ old('status', $query->status ?? "") == 0 ? "checked" : "" }} id="edit_status_no">
                        <label for="edit_status_no">Status No</label>
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