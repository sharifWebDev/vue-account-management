<form><div class="row"><div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="account_name">Account Name</label><br><input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name', $query->account_name ?? "") }}" placeholder="Enter Account Name..." id="view_account_name" disabled>@error('account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="account_number">Account Number</label><br><input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $query->account_number ?? "") }}" placeholder="Enter Account Number..." id="view_account_number" disabled>@error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="company_id">Company Id</label><br><input type="number" name="company_id" min="0" class="form-control @error('company_id') is-invalid @enderror" value="{{ old('company_id', $query->company_id ?? "") }}" placeholder="Enter Company Id..." id="view_company_id" disabled>@error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="bank_id">Bank Id</label><br><input type="number" name="bank_id" min="0" class="form-control @error('bank_id') is-invalid @enderror" value="{{ old('bank_id', $query->bank_id ?? "") }}" placeholder="Enter Bank Id..." id="view_bank_id" disabled>@error('bank_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="branch_id">Branch Id</label><br><input type="number" name="branch_id" min="0" class="form-control @error('branch_id') is-invalid @enderror" value="{{ old('branch_id', $query->branch_id ?? "") }}" placeholder="Enter Branch Id..." id="view_branch_id" disabled>@error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="cheque_book">Cheque Book</label><br><textarea name="cheque_book" class="form-control @error('cheque_book') is-invalid @enderror" placeholder="Enter Cheque Book..." id="view_cheque_book" disabled>{{ old('cheque_book', $query->cheque_book ?? "") }}</textarea>@error('cheque_book')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="opening_balance">Opening Balance</label><br><input type="number" step="any" name="opening_balance" min="0" class="form-control @error('opening_balance') is-invalid @enderror" value="{{ old('opening_balance', $query->opening_balance ?? "") }}" placeholder="Enter Opening Balance..." id="view_opening_balance" disabled>@error('opening_balance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="created">Created</label><br><input type="datetime-local" name="created" class="form-control @error('created') is-invalid @enderror" value="{{ old('created', $query->created ?? "") }}" placeholder="Enter Created..." id="view_created" disabled>@error('created')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="modified">Modified</label><br><input type="datetime-local" name="modified" class="form-control @error('modified') is-invalid @enderror" value="{{ old('modified', $query->modified ?? "") }}" placeholder="Enter Modified..." id="view_modified" disabled>@error('modified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="status">Status</label><br><input type="radio" name="status" value="1" {{ old('status', $query->status ?? "") == 1 ? "checked" : "" }} id="view_status_yes" disabled> 
                                   <label for="view_status_yes" disabled>Status Yes </label>
                                   <input type="radio" name="status" value="0" {{ old('status', $query->status ?? "") == 0 ? "checked" : "" }} id="view_status_no" disabled> 
                                   <label for="view_status_no" disabled>Status No </label>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div><div class="mb-3 text-right">
                              <a type="button" class="btn bg-danger text-white" href="{{ route('admin.accounts.index') }}">Close</a>
                          </div>
                      </div>
                  </form>