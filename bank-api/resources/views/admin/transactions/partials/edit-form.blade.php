
            <form method="POST" id="edittransactionsForm" action="{{ url('api/v1/transactions/update/' . request()->id ?? "") }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="date">Date</label><br>
                        <input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $query->date ?? "") }}" id="edit_date" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="type">Type</label><br>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $query->type ?? "") }}" placeholder="Enter Type..." id="edit_type" required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="details">Details</label><br>
                        <textarea name="details" class="form-control @error('details') is-invalid @enderror" placeholder="Enter Details..." id="edit_details" required>{{ old('details', $query->details ?? "") }}</textarea>
                        @error('details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="deposit">Deposit</label><br>
                        <input type="number" step="any" name="deposit" min="0" class="form-control @error('deposit') is-invalid @enderror" value="{{ old('deposit', $query->deposit ?? "") }}" placeholder="Enter Deposit..." id="edit_deposit" required>
                        @error('deposit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="withdraw">Withdraw</label><br>
                        <input type="number" step="any" name="withdraw" min="0" class="form-control @error('withdraw') is-invalid @enderror" value="{{ old('withdraw', $query->withdraw ?? "") }}" placeholder="Enter Withdraw..." id="edit_withdraw" required>
                        @error('withdraw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="receive_from">Receive From</label><br>
                        <input type="text" name="receive_from" class="form-control @error('receive_from') is-invalid @enderror" value="{{ old('receive_from', $query->receive_from ?? "") }}" placeholder="Enter Receive From..." id="edit_receive_from" required>
                        @error('receive_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="payment_to">Payment To</label><br>
                        <input type="text" name="payment_to" class="form-control @error('payment_to') is-invalid @enderror" value="{{ old('payment_to', $query->payment_to ?? "") }}" placeholder="Enter Payment To..." id="edit_payment_to" required>
                        @error('payment_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="attachment">Attachment</label><br>
                        <input type="text" name="attachment" class="form-control @error('attachment') is-invalid @enderror" value="{{ old('attachment', $query->attachment ?? "") }}" placeholder="Enter Attachment..." id="edit_attachment" required>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="user_id">User Id</label><br>
                        <input type="number" name="user_id" min="0" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $query->user_id ?? "") }}" placeholder="Enter User Id..." id="edit_user_id" required>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="account_id">Account Id</label><br>
                        <input type="number" name="account_id" min="0" class="form-control @error('account_id') is-invalid @enderror" value="{{ old('account_id', $query->account_id ?? "") }}" placeholder="Enter Account Id..." id="edit_account_id" required>
                        @error('account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="bounce_transaction_id">Bounce Transaction Id</label><br>
                        <input type="number" name="bounce_transaction_id" min="0" class="form-control @error('bounce_transaction_id') is-invalid @enderror" value="{{ old('bounce_transaction_id', $query->bounce_transaction_id ?? "") }}" placeholder="Enter Bounce Transaction Id..." id="edit_bounce_transaction_id" required>
                        @error('bounce_transaction_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-12 col-md-4 col-lg-3">
                    <div class="form-group">
                        <label for="bounce_details">Bounce Details</label><br>
                        <input type="text" name="bounce_details" class="form-control @error('bounce_details') is-invalid @enderror" value="{{ old('bounce_details', $query->bounce_details ?? "") }}" placeholder="Enter Bounce Details..." id="edit_bounce_details" required>
                        @error('bounce_details')
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
                    <a type="button" class="btn bg-danger text-white" href="{{ route('admin.transactions.index') }}">Cancel</a>
                    <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>