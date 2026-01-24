<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {

            return view('admin.banks.index');

        } catch (Exception $e) {

            info('Error showing Banks!', [$e]);

            return redirect()->back()->with('error', 'Banks showing failed!.');

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  Bank  $singularTableName
     * @return \Illuminate\View\View
     */
    public function create(): View|RedirectResponse
    {
        try {

            return view('admin.banks.create');

        } catch (Exception $e) {

            info('Error showing Banks!', [$e]);

            return redirect()->back()->with('error', 'Banks showing failed!.');

        }
    }

    /**
     * Edit the specified resource.
     *
     * @param  Request  $request
     * @param  Bank  $singularTableName
     * @return \Illuminate\View\View
     */
    public function edit(): View|RedirectResponse
    {
        try {

            return view('admin.banks.edit');

        } catch (Exception $e) {

            info('Error showing Banks!', [$e]);

            return redirect()->back()->with('error', 'Banks showing failed!.');

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

            return view('admin.banks.view');

        } catch (\Exception $e) {

            info('Banks data showing failed!', [$e]);

            return redirect()->back()->with('error', 'Banks showing failed!.');
        }
    }
}
