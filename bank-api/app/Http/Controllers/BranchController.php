<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {

            return view('admin.branches.index');

        } catch (Exception $e) {

            info('Error showing Branches!', [$e]);

            return redirect()->back()->with('error', 'Branches showing failed!.');

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  Branch  $singularTableName
     * @return \Illuminate\View\View
     */
    public function create(): View|RedirectResponse
    {
        try {

            return view('admin.branches.create');

        } catch (Exception $e) {

            info('Error showing Branches!', [$e]);

            return redirect()->back()->with('error', 'Branches showing failed!.');

        }
    }

    /**
     * Edit the specified resource.
     *
     * @param  Request  $request
     * @param  Branch  $singularTableName
     * @return \Illuminate\View\View
     */
    public function edit(): View|RedirectResponse
    {
        try {

            return view('admin.branches.edit');

        } catch (Exception $e) {

            info('Error showing Branches!', [$e]);

            return redirect()->back()->with('error', 'Branches showing failed!.');

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

            return view('admin.branches.view');

        } catch (\Exception $e) {

            info('Branches data showing failed!', [$e]);

            return redirect()->back()->with('error', 'Branches showing failed!.');
        }
    }
}
