<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ReasonsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reasons = Reason::all();
        return view('reasons.index')-> with ('reasons', $reasons);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(){
    /* $reasons = Reason::all();
        $reasons = Reason::orderBy('descricao')->get();
        return view('vehicle_movements', compact('reasons'));*/
    }
    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {

        $validated = request()->validate([
            'descricao' => 'required|string|max:255|unique:reasons,descricao',
        ]);

        Reason::create($validated);

        return redirect()->route('reasons.index')->with('success', 'Motivo criado com sucesso');
    }


    public function reason(request $request){
        $request->validate([
            'reason_id' => 'required|exists:reasons,id',

        ]);
        $reason = Reason::find($request->reasons_id);
        return redirect()->route('reasons.index', ['reason' => $reason]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reason $reasons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reason $reasons)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reason $reasons)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reason $reasons)
    {
        //
    }
}
