<?php

namespace App\Http\Controllers;

use App\Models\Spend;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spend = Spend::paginate(20);
        $total = $spend->sum('amount');
        return view('spend.index', compact('spend', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
        ]);

        $spend = new Spend();
        $spend->name = $request->name;
        $spend->amount = $request->amount;
        $spend->save();

        return redirect('/')->with('success', 'Data Spend has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $spend = Spend::where('id', $id)->first();
        return view('spend.edit', compact('spend'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $spend = Spend::find($request->id);
        $spend->name = $request->name;
        $spend->amount = $request->amount;
        $spend->save();

        return redirect('/')->with('success', 'Data Spend has been updated!');
    }

    public function getSpendData(Request $request)
    {
        // $start = $request->query('start');
        // $end = $request->query('end');

        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $startDate->addDay();
        $endDate->addDay();
        $end = $endDate->endOfDay();

        Log::info('Fetching spend data', ['start' => $startDate, 'end' => $end]);

        $spend = Spend::whereBetween('created_at', [$startDate, $end])->get();

        return response()->json($spend);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
