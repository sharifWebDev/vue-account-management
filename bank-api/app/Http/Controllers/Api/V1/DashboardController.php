<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get comprehensive dashboard data
     */
    public function dashboard(Request $request)
    {
        try {
            // Get time range filters
            $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->toDateString());
            $companyId = $request->input('company_id');
            $accountId = $request->input('account_id');

            // Convert dates to Carbon instances
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // Base queries with filters
            $accountQuery = Account::query();
            $transactionQuery = Transaction::query();
            $companyQuery = Company::query();

            // Apply company filter
            if ($companyId) {
                $accountQuery->where('company_id', $companyId);
                $transactionQuery->whereHas('account', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            }

            // Apply account filter
            if ($accountId) {
                $transactionQuery->where('account_id', $accountId);
            }

            // Apply date filter to transactions
            $transactionQuery->whereBetween('date', [$start, $end]);

            // Get counts
            $totalAccounts = Account::whereNull('deleted_at')->count();
            $activeCompanies = Company::where('status', 1)->whereNull('deleted_at')->count();
            $totalUsers = User::where('status', 1)->whereNull('deleted_at')->count();

            // Calculate total balance
            $accounts = Account::whereNull('deleted_at')->get();
            $totalBalance = 0;

            foreach ($accounts as $account) {
                $totalBalance += $this->calculateAccountBalance($account->id);
            }

            // Get recent transactions count for the selected period
            $recentTransactionsCount = $transactionQuery->whereNull('deleted_at')->count();

            // Calculate accounts growth (this month vs last month)
            $accountsGrowth = $this->calculateAccountsGrowth();

            // Calculate balance change (this month vs last month)
            $balanceChange = $this->calculateBalanceChange();

            // Get recent accounts for the table
            $recentAccounts = $accountQuery->with(['company', 'bank', 'branch'])
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Get recent transactions for display
            $recentTransactions = $transactionQuery->with(['account', 'account.company', 'user'])
                ->whereNull('deleted_at')
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();

            // Get company distribution with account counts
            $companyDistribution = $this->getCompanyDistribution();

            // Get transaction types distribution
            $transactionTypes = $this->getTransactionTypeDistribution($start, $end, $companyId);

            // Get daily transaction trends
            $dailyTrends = $this->getDailyTransactionTrends($start, $end);

            // Get top accounts by balance
            $topAccounts = $this->getTopAccountsByBalance(5);

            // Get recent system activities
            $systemActivity = $this->getSystemActivity();

            // Get monthly summary
            $monthlySummary = $this->getMonthlySummary();

            // Get bank distribution
            $bankDistribution = $this->getBankDistribution();

            // Calculate transaction statistics
            $transactionStats = $this->getTransactionStatistics($start, $end);

            // Get all companies for filter dropdown
            $allCompanies = Company::whereNull('deleted_at')
                ->orderBy('company_name')
                ->get(['id', 'company_name']);

            // Get all accounts for filter dropdown
            $allAccounts = Account::with('company')
                ->whereNull('deleted_at')
                ->orderBy('account_name')
                ->get(['id', 'account_name', 'account_number', 'company_id']);

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => [
                        'totalAccounts' => $totalAccounts,
                        'totalBalance' => $totalBalance,
                        'activeCompanies' => $activeCompanies,
                        'recentTransactionsCount' => $recentTransactionsCount,
                        'accountsGrowth' => $accountsGrowth,
                        'balanceChange' => $balanceChange,
                        'totalUsers' => $totalUsers,
                        'averageTransactionValue' => $transactionStats['average_value'],
                        'totalDeposits' => $transactionStats['total_deposits'],
                        'totalWithdrawals' => $transactionStats['total_withdrawals'],
                        'netFlow' => $transactionStats['net_flow'],
                        'totalTransactions' => $transactionStats['transaction_count'],
                    ],
                    'accounts' => $recentAccounts->map(function ($account) {
                        return [
                            'id' => $account->id,
                            'account_name' => $account->account_name,
                            'account_number' => $account->account_number,
                            'company_id' => $account->company_id,
                            'company_name' => $account->company->company_name ?? 'Unknown',
                            'bank_id' => $account->bank_id,
                            'bank_name' => $account->bank->bank_name ?? 'Unknown',
                            'branch_id' => $account->branch_id,
                            'branch_name' => $account->branch->branch_name ?? 'Unknown',
                            'status' => $account->status,
                            'balance' => $this->calculateAccountBalance($account->id),
                            'opening_balance' => $account->opening_balance,
                            'created_at' => $account->created_at,
                            'last_transaction_date' => $this->getLastTransactionDate($account->id),
                        ];
                    })->toArray(),
                    'recentTransactions' => $recentTransactions->map(function ($transaction) {
                        return [
                            'id' => $transaction->id,
                            'date' => $transaction->date,
                            'type' => $transaction->type,
                            'details' => $transaction->details,
                            'deposit' => $transaction->deposit,
                            'withdraw' => $transaction->withdraw,
                            'receive_from' => $transaction->receive_from,
                            'payment_to' => $transaction->payment_to,
                            'account_id' => $transaction->account_id,
                            'account_name' => $transaction->account->account_name ?? 'Unknown',
                            'account_number' => $transaction->account->account_number ?? 'Unknown',
                            'company_name' => $transaction->account->company->company_name ?? 'Unknown',
                            'created_by' => $transaction->user ? ($transaction->user->first_name . ' ' . $transaction->user->last_name) : 'System',
                            'status' => $transaction->status,
                        ];
                    })->toArray(),
                    'companyDistribution' => $companyDistribution->toArray(),
                    'transactionTypes' => $transactionTypes->toArray(),
                    'dailyTrends' => $dailyTrends,
                    'topAccounts' => $topAccounts,
                    'systemActivity' => $systemActivity,
                    'monthlySummary' => $monthlySummary,
                    'bankDistribution' => $bankDistribution->toArray(),
                    'filters' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'company_id' => $companyId,
                        'account_id' => $accountId,
                    ],
                    'filterOptions' => [
                        'companies' => $allCompanies->map(function ($company) {
                            return [
                                'id' => $company->id,
                                'name' => $company->company_name
                            ];
                        })->toArray(),
                        'accounts' => $allAccounts->map(function ($account) {
                            return [
                                'id' => $account->id,
                                'name' => $account->account_name . ' (' . $account->account_number . ')',
                                'company_name' => $account->company->company_name ?? 'Unknown'
                            ];
                        })->toArray(),
                    ],
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'user' => Auth::check() ? [
                        'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                        'email' => Auth::user()->email,
                        'role' => Auth::user()->user_type,
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Calculate account balance from transactions
     */
    private function calculateAccountBalance($accountId)
    {
        $balance = Transaction::where('account_id', $accountId)
            ->whereNull('deleted_at')
            ->select(DB::raw('COALESCE(SUM(deposit - withdraw), 0) as balance'))
            ->value('balance');

        // Add opening balance from accounts table
        $openingBalance = Account::where('id', $accountId)
            ->value('opening_balance') ?? 0;

        return $balance + floatval($openingBalance);
    }

    /**
     * Calculate accounts growth (this month vs last month)
     */
    private function calculateAccountsGrowth()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $accountsThisMonth = Account::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->whereNull('deleted_at')
            ->count();

        $accountsLastMonth = Account::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->whereNull('deleted_at')
            ->count();

        return $accountsThisMonth - $accountsLastMonth;
    }

    /**
     * Calculate balance change (this month vs last month)
     */
    private function calculateBalanceChange()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Calculate current month net flow
        $currentMonthNet = Transaction::where('date', '>=', $currentMonthStart)
            ->whereNull('deleted_at')
            ->select(DB::raw('COALESCE(SUM(deposit - withdraw), 0) as net'))
            ->value('net');

        // Calculate last month net flow
        $lastMonthNet = Transaction::whereBetween('date', [$lastMonthStart, $lastMonthEnd])
            ->whereNull('deleted_at')
            ->select(DB::raw('COALESCE(SUM(deposit - withdraw), 0) as net'))
            ->value('net');

        return floatval($currentMonthNet) - floatval($lastMonthNet);
    }

    /**
     * Get company distribution with account counts
     */
    private function getCompanyDistribution()
    {
        return Company::with(['accounts' => function ($query) {
            $query->whereNull('deleted_at');
        }])
            ->whereNull('deleted_at')
            ->orderBy('company_name')
            ->get()
            ->map(function ($company) {
                $accountCount = $company->accounts->count();
                $totalBalance = 0;

                foreach ($company->accounts as $account) {
                    $totalBalance += $this->calculateAccountBalance($account->id);
                }

                return [
                    'company_id' => $company->id,
                    'company_name' => $company->company_name,
                    'account_count' => $accountCount,
                    'total_balance' => $totalBalance,
                    'status' => $company->status,
                ];
            });
    }

    /**
     * Get transaction type distribution
     */
    private function getTransactionTypeDistribution($startDate, $endDate, $companyId = null)
    {
        $query = Transaction::select('type', DB::raw('COUNT(*) as count'))
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNull('deleted_at');

        if ($companyId) {
            $query->whereHas('account', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        return $query->groupBy('type')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($type) {
                return [
                    'name' => $type->type,
                    'count' => $type->count,
                    'icon' => $this->getTransactionTypeIcon($type->type),
                    'color' => $this->getTransactionTypeColor($type->type)
                ];
            });
    }

    /**
     * Get daily transaction trends
     */
    private function getDailyTransactionTrends($startDate, $endDate)
    {
        $trends = Transaction::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(deposit) as total_deposit'),
            DB::raw('SUM(withdraw) as total_withdraw'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get();

        // Fill missing dates
        $allDates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $allDates[$currentDate->toDateString()] = [
                'date' => $currentDate->toDateString(),
                'total_deposit' => 0,
                'total_withdraw' => 0,
                'transaction_count' => 0,
                'net_flow' => 0
            ];
            $currentDate->addDay();
        }

        foreach ($trends as $trend) {
            $date = Carbon::parse($trend->date)->toDateString();
            $netFlow = floatval($trend->total_deposit) - floatval($trend->total_withdraw);

            $allDates[$date] = [
                'date' => $date,
                'total_deposit' => floatval($trend->total_deposit),
                'total_withdraw' => floatval($trend->total_withdraw),
                'transaction_count' => $trend->transaction_count,
                'net_flow' => $netFlow
            ];
        }

        return array_values($allDates);
    }

    /**
     * Get top accounts by balance
     */
    private function getTopAccountsByBalance($limit = 5)
    {
        $accounts = Account::with(['company', 'bank'])
            ->whereNull('deleted_at')
            ->get();

        $accountsWithBalance = [];
        foreach ($accounts as $account) {
            $balance = $this->calculateAccountBalance($account->id);
            $accountsWithBalance[] = [
                'account_id' => $account->id,
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'company_name' => $account->company->company_name ?? 'Unknown',
                'bank_name' => $account->bank->bank_name ?? 'Unknown',
                'balance' => $balance,
                'status' => $account->status,
            ];
        }

        // Sort by balance descending and take top N
        usort($accountsWithBalance, function ($a, $b) {
            return $b['balance'] <=> $a['balance'];
        });

        return array_slice($accountsWithBalance, 0, $limit);
    }

    /**
     * Get system activity
     */
    private function getSystemActivity()
    {
        $activities = [];

        try {
            // Recent user logins
            $recentLogins = DB::table('personal_access_tokens')
                ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
                ->select('users.first_name', 'users.last_name', 'personal_access_tokens.last_used_at')
                ->where('tokenable_type', 'App\Models\User')
                ->whereNotNull('last_used_at')
                ->orderBy('last_used_at', 'desc')
                ->take(3)
                ->get();

            foreach ($recentLogins as $login) {
                $activities[] = [
                    'title' => 'User login',
                    'description' => $login->first_name . ' ' . $login->last_name . ' logged in',
                    'timestamp' => $login->last_used_at,
                    'icon' => 'fas fa-sign-in-alt',
                    'color' => 'bg-blue-100 dark:bg-blue-900/30',
                    'type' => 'login'
                ];
            }

            // Recent account creations
            $recentAccounts = Account::with('company')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get();

            foreach ($recentAccounts as $account) {
                $activities[] = [
                    'title' => 'New account added',
                    'description' => $account->account_name . ' (' . ($account->company->company_name ?? 'Unknown') . ')',
                    'timestamp' => $account->created_at,
                    'icon' => 'fas fa-landmark',
                    'color' => 'bg-green-100 dark:bg-green-900/30',
                    'type' => 'account_creation'
                ];
            }

            // Recent transactions
            $recentTransactions = Transaction::with('account')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            foreach ($recentTransactions as $transaction) {
                $type = $transaction->deposit > 0 ? 'Deposit' : 'Withdrawal';
                $activities[] = [
                    'title' => $type . ' transaction',
                    'description' => $transaction->details . ' - ' . ($transaction->account->account_name ?? 'Unknown'),
                    'timestamp' => $transaction->created_at,
                    'icon' => $transaction->deposit > 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up',
                    'color' => $transaction->deposit > 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30',
                    'type' => 'transaction'
                ];
            }
        } catch (\Exception $e) {
            \Log::error('System Activity Error: ' . $e->getMessage());
        }

        // Sort by timestamp descending
        usort($activities, function ($a, $b) {
            return strtotime($b['timestamp'] ?? '') <=> strtotime($a['timestamp'] ?? '');
        });

        return array_slice($activities, 0, 5);
    }

    /**
     * Get monthly summary
     */
    private function getMonthlySummary()
    {
        $months = [];

        try {
            $currentMonth = Carbon::now()->startOfMonth();

            for ($i = 0; $i < 6; $i++) {
                $monthStart = $currentMonth->copy()->subMonths($i)->startOfMonth();
                $monthEnd = $currentMonth->copy()->subMonths($i)->endOfMonth();

                $transactions = Transaction::whereBetween('date', [$monthStart, $monthEnd])
                    ->whereNull('deleted_at')
                    ->select(
                        DB::raw('SUM(deposit) as total_deposit'),
                        DB::raw('SUM(withdraw) as total_withdraw'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->first();

                $months[] = [
                    'month' => $monthStart->format('M Y'),
                    'total_deposit' => floatval($transactions->total_deposit ?? 0),
                    'total_withdraw' => floatval($transactions->total_withdraw ?? 0),
                    'transaction_count' => $transactions->transaction_count ?? 0,
                    'net_flow' => floatval($transactions->total_deposit ?? 0) - floatval($transactions->total_withdraw ?? 0),
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Monthly Summary Error: ' . $e->getMessage());
        }

        return array_reverse($months);
    }

    /**
     * Get bank distribution
     */
    private function getBankDistribution()
    {
        try {
            return Bank::with(['accounts' => function ($query) {
                $query->whereNull('deleted_at');
            }])
                ->whereNull('deleted_at')
                ->orderBy('bank_name')
                ->get()
                ->map(function ($bank) {
                    $accountCount = $bank->accounts->count();
                    $totalBalance = 0;

                    foreach ($bank->accounts as $account) {
                        $totalBalance += $this->calculateAccountBalance($account->id);
                    }

                    return [
                        'bank_id' => $bank->id,
                        'bank_name' => $bank->bank_name,
                        'account_count' => $accountCount,
                        'total_balance' => $totalBalance,
                        'status' => $bank->status,
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Bank Distribution Error: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get transaction statistics
     */
    private function getTransactionStatistics($startDate, $endDate)
    {
        try {
            $stats = Transaction::whereBetween('date', [$startDate, $endDate])
                ->whereNull('deleted_at')
                ->select(
                    DB::raw('SUM(deposit) as total_deposits'),
                    DB::raw('SUM(withdraw) as total_withdrawals'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('AVG(COALESCE(deposit, 0) + COALESCE(withdraw, 0)) as average_value')
                )
                ->first();

            return [
                'total_deposits' => floatval($stats->total_deposits ?? 0),
                'total_withdrawals' => floatval($stats->total_withdrawals ?? 0),
                'transaction_count' => $stats->transaction_count ?? 0,
                'average_value' => floatval($stats->average_value ?? 0),
                'net_flow' => floatval($stats->total_deposits ?? 0) - floatval($stats->total_withdrawals ?? 0),
            ];
        } catch (\Exception $e) {
            \Log::error('Transaction Statistics Error: ' . $e->getMessage());
            return [
                'total_deposits' => 0,
                'total_withdrawals' => 0,
                'transaction_count' => 0,
                'average_value' => 0,
                'net_flow' => 0,
            ];
        }
    }

    /**
     * Get last transaction date for an account
     */
    private function getLastTransactionDate($accountId)
    {
        try {
            return Transaction::where('account_id', $accountId)
                ->whereNull('deleted_at')
                ->orderBy('date', 'desc')
                ->value('date');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get transaction type icon
     */
    private function getTransactionTypeIcon($type)
    {
        $icons = [
            'Balance' => 'fas fa-balance-scale',
            'Cheque' => 'fas fa-money-check',
            'Cash' => 'fas fa-money-bill-wave',
            'Debit/Credit Card' => 'fas fa-credit-card',
            'Bank Transfer' => 'fas fa-exchange-alt',
            'Other' => 'fas fa-exchange-alt',
            'Opening Balance' => 'fas fa-plus-circle',
            'Deposit' => 'fas fa-arrow-down',
            'Withdrawal' => 'fas fa-arrow-up',
        ];

        return $icons[$type] ?? 'fas fa-exchange-alt';
    }

    /**
     * Get transaction type color
     */
    private function getTransactionTypeColor($type)
    {
        $colors = [
            'Balance' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
            'Cheque' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
            'Cash' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
            'Debit/Credit Card' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
            'Bank Transfer' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400',
            'Other' => 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400',
            'Opening Balance' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
            'Deposit' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
            'Withdrawal' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
        ];

        return $colors[$type] ?? 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400';
    }

    /**
     * Quick stats endpoint for fast loading
     */
    public function quickStats()
    {
        try {
            $totalAccounts = Account::whereNull('deleted_at')->count();

            $totalBalance = Account::sum('opening_balance') +
                Transaction::whereNull('deleted_at')->sum('deposit') -
                Transaction::whereNull('deleted_at')->sum('withdraw');

            $todayTransactions = Transaction::whereDate('date', Carbon::today())
                ->whereNull('deleted_at')
                ->count();

            $activeUsers = User::where('status', 1)
                ->whereNull('deleted_at')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalAccounts' => $totalAccounts,
                    'totalBalance' => floatval($totalBalance),
                    'todayTransactions' => $todayTransactions,
                    'activeUsers' => $activeUsers,
                    'timestamp' => Carbon::now()->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching quick stats'
            ], 500);
        }
    }

    /**
     * Export dashboard data
     */
    public function export(Request $request)
    {
        try {
            $data = $this->dashboard($request)->getData(true);

            if (!$data['success']) {
                return response()->json($data, 500);
            }

            $format = $request->input('format', 'json');

            if ($format === 'json') {
                return response()->json($data);
            }

            // You can add CSV or Excel export here
            return response()->json([
                'success' => false,
                'message' => 'Export format not supported yet'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting data'
            ], 500);
        }
    }

    /**
     * Get chart-specific data
     */
    public function getChartData(Request $request)
    {
        try {
            $chartType = $request->input('chart_type', 'daily_trends');
            $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->toDateString());

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            switch ($chartType) {
                case 'daily_trends':
                    $data = $this->getDailyTransactionTrends($start, $end);
                    break;
                case 'monthly_summary':
                    $data = $this->getMonthlySummary();
                    break;
                case 'transaction_types':
                    $data = $this->getTransactionTypeDistribution($start, $end);
                    break;
                case 'company_distribution':
                    $data = $this->getCompanyDistribution();
                    break;
                case 'bank_distribution':
                    $data = $this->getBankDistribution();
                    break;
                default:
                    $data = [];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'chart_type' => $chartType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching chart data',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get dashboard summary for quick view
     */
    public function getQuickStats()
    {
        try {
            $totalAccounts = Account::whereNull('deleted_at')->count();
            $totalBalance = Account::sum('opening_balance') +
                Transaction::whereNull('deleted_at')->sum('deposit') -
                Transaction::whereNull('deleted_at')->sum('withdraw');

            $todayTransactions = Transaction::whereDate('date', Carbon::today())
                ->whereNull('deleted_at')
                ->count();

            $pendingTransactions = Transaction::where('status', 0)
                ->whereNull('deleted_at')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalAccounts' => $totalAccounts,
                    'totalBalance' => $totalBalance,
                    'todayTransactions' => $todayTransactions,
                    'pendingTransactions' => $pendingTransactions,
                    'activeUsers' => User::where('status', 1)->whereNull('deleted_at')->count(),
                ],
                'timestamp' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching quick stats'
            ], 500);
        }
    }
}
