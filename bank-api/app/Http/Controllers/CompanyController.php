<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {

            return view('admin.companies.index');

        } catch (Exception $e) {

            info('Error showing Companies!', [$e]);

            return redirect()->back()->with('error', 'Companies showing failed!.');

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  Company  $singularTableName
     * @return \Illuminate\View\View
     */
    public function create(): View|RedirectResponse
    {
        try {

            return view('admin.companies.create');

        } catch (Exception $e) {

            info('Error showing Companies!', [$e]);

            return redirect()->back()->with('error', 'Companies showing failed!.');

        }
    }

    /**
     * Edit the specified resource.
     *
     * @param  Request  $request
     * @param  Company  $singularTableName
     * @return \Illuminate\View\View
     */
    public function edit(): View|RedirectResponse
    {
        try {

            return view('admin.companies.edit');

        } catch (Exception $e) {

            info('Error showing Companies!', [$e]);

            return redirect()->back()->with('error', 'Companies showing failed!.');

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

            return view('admin.companies.view');

        } catch (\Exception $e) {

            info('Companies data showing failed!', [$e]);

            return redirect()->back()->with('error', 'Companies showing failed!.');
        }
    }
}
