<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {

            return view('admin.transactions.index');

        } catch (Exception $e) {

            info('Error showing Transactions!', [$e]);

            return redirect()->back()->with('error', 'Transactions showing failed!.');

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  Transaction  $singularTableName
     * @return \Illuminate\View\View
     */
    public function create(): View|RedirectResponse
    {
        try {

            return view('admin.transactions.create');

        } catch (Exception $e) {

            info('Error showing Transactions!', [$e]);

            return redirect()->back()->with('error', 'Transactions showing failed!.');

        }
    }

    /**
     * Edit the specified resource.
     *
     * @param  Request  $request
     * @param  Transaction  $singularTableName
     * @return \Illuminate\View\View
     */
    public function edit(): View|RedirectResponse
    {
        try {

            return view('admin.transactions.edit');

        } catch (Exception $e) {

            info('Error showing Transactions!', [$e]);

            return redirect()->back()->with('error', 'Transactions showing failed!.');

        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View|RedirectResponse
    {
        try {

            return view('admin.transactions.view');

        } catch (\Exception $e) {

            info('Transactions data showing failed!', [$e]);

            return redirect()->back()->with('error', 'Transactions showing failed!.');
        }
    }
}
