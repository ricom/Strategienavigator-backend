<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Tool2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $tools = Tool::paginate(20);
        return view('tools.index', ['tools' => $tools]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('tools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'tutorial' => 'required'
        ]);

        $tool = new Tool();
        $tool->name = $request->name;
        $tool->status = $request->status;
        $tool->tutorial = $request->tutorial;
        $tool->save();
        return redirect()->route('tools.index')
            ->with('success', 'Tool created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $tool = Tool::find($id);
        return view('tools.show', ['tool' => $tool]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id): View|Factory|Application
    {
        $tool = Tool::find($id);
        return view('tools.edit', ['tool' => $tool]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'tutorial' => 'required'
        ]);


        $tool = Tool::find($id);
        $tool->name = $request->name;
        $tool->status = $request->status;
        $tool->tutorial = $request->tutorial;
        $tool->save();
        return redirect()->route('tools.index')
            ->with('success', 'Tool updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        Tool::destroy($id);
        return redirect()->route('tools.index')
            ->with('success', 'Tool deleted successfully.');
    }
}
