<form><div class="row"><div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="date">Date</label><br><input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $query->date ?? "") }}" placeholder="Enter Date..." id="view_date" disabled>@error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="type">Type</label><br><input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $query->type ?? "") }}" placeholder="Enter Type..." id="view_type" disabled>@error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="details">Details</label><br><textarea name="details" class="form-control @error('details') is-invalid @enderror" placeholder="Enter Details..." id="view_details" disabled>{{ old('details', $query->details ?? "") }}</textarea>@error('details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="deposit">Deposit</label><br><input type="number" step="any" name="deposit" min="0" class="form-control @error('deposit') is-invalid @enderror" value="{{ old('deposit', $query->deposit ?? "") }}" placeholder="Enter Deposit..." id="view_deposit" disabled>@error('deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="withdraw">Withdraw</label><br><input type="number" step="any" name="withdraw" min="0" class="form-control @error('withdraw') is-invalid @enderror" value="{{ old('withdraw', $query->withdraw ?? "") }}" placeholder="Enter Withdraw..." id="view_withdraw" disabled>@error('withdraw')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="receive_from">Receive From</label><br><input type="text" name="receive_from" class="form-control @error('receive_from') is-invalid @enderror" value="{{ old('receive_from', $query->receive_from ?? "") }}" placeholder="Enter Receive From..." id="view_receive_from" disabled>@error('receive_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="payment_to">Payment To</label><br><input type="text" name="payment_to" class="form-control @error('payment_to') is-invalid @enderror" value="{{ old('payment_to', $query->payment_to ?? "") }}" placeholder="Enter Payment To..." id="view_payment_to" disabled>@error('payment_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="attachment">Attachment</label><br><input type="text" name="attachment" class="form-control @error('attachment') is-invalid @enderror" value="{{ old('attachment', $query->attachment ?? "") }}" placeholder="Enter Attachment..." id="view_attachment" disabled>@error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="user_id">User Id</label><br><input type="number" name="user_id" min="0" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $query->user_id ?? "") }}" placeholder="Enter User Id..." id="view_user_id" disabled>@error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="account_id">Account Id</label><br><input type="number" name="account_id" min="0" class="form-control @error('account_id') is-invalid @enderror" value="{{ old('account_id', $query->account_id ?? "") }}" placeholder="Enter Account Id..." id="view_account_id" disabled>@error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="bounce_transaction_id">Bounce Transaction Id</label><br><input type="number" name="bounce_transaction_id" min="0" class="form-control @error('bounce_transaction_id') is-invalid @enderror" value="{{ old('bounce_transaction_id', $query->bounce_transaction_id ?? "") }}" placeholder="Enter Bounce Transaction Id..." id="view_bounce_transaction_id" disabled>@error('bounce_transaction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="bounce_details">Bounce Details</label><br><input type="text" name="bounce_details" class="form-control @error('bounce_details') is-invalid @enderror" value="{{ old('bounce_details', $query->bounce_details ?? "") }}" placeholder="Enter Bounce Details..." id="view_bounce_details" disabled>@error('bounce_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                              <a type="button" class="btn bg-danger text-white" href="{{ route('admin.transactions.index') }}">Close</a>
                          </div>
                      </div>
                  </form>