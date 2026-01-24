<div class="container m-0 p-0">
        <!-- Modern Table with Export & Column Visibility -->
         <div class="dt-container m-0 p-0">
            <table id="leadType" class="table table-modern"
                data-dt
                data-title="Accounts List"
                data-index-url="{{ url('api/v1/accounts') }}"
                data-create-url="{{ url('admin/accounts/create') }}"
                data-edit-url="{{ route('admin.accounts.edit', ':id') }}"
                data-delete-url="{{ url('api/v1/accounts/destroy') }}/:id"
                data-show-url="{{ route('admin.accounts.show', ':id') }}"
                data-fields='["account_name", "account_number", "company_id", "bank_id", "branch_id", "cheque_book", "opening_balance", "created", "modified", "status"]'
                data-headers='["Account Name", "Account Number", "Company", "Bank", "Branch", "Cheque Book", "Opening Balance", "Created", "Modified", "Status"]'
                data-filters=''
                {{-- data-filters=']' --}}
                data-export="true"
                data-colvis="true"
                data-csv="true"
                data-excel="true"
                data-pdf="true"
                data-print="true">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- <!-- Quick Actions -->
        <div class="mt-3">
            <button class="btn btn-primary me-2" onclick="dtHelpers.exportData('Account', 'excel')">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div> --}}
    </div>


    <script>
       // Custom status renderer for boolean status values
        setTimeout(() => {
            if (window.dtHelpers) {
                dtHelpers.registerRenderer('Account', 'status', function(data, row) {
                    if (data === true || data === 1 || data === '1') {
                        return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>';
                    } else if (data === false || data === 0 || data === '0') {
                        return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>';
                    }
                    return '<span class="badge bg-secondary">' + data + '</span>';
                });
            }
        }, 1000);
    </script>
    {{-- @include('components.datatables'); --}}
    