<?php

namespace App\Http\Controllers;

use App\Exports\SpendsExport;
use App\Imports\SpendsImport;
use App\Models\Spend;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SpendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();

        // Ambil data spend berdasarkan user_id
        $spend = Spend::where('user_id', $user_id)->get();
        $total = $spend->sum('amount');

        // Paginate (opsional, jika ingin menggunakan paginate)
        // $spend = Spend::where('user_id', $user_id)->paginate(20);
        // $total = $spend->sum('amount');

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
        $spend->user_id = Auth::id();  // Set user_id dari pengguna yang sedang login
        $spend->name = $request->name;
        $spend->amount = $request->amount;
        $spend->save();

        return redirect('/spend')->with('success', 'Data Spend has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(String $id)
    {
        $user_id = Auth::id();

        // Ambil spend berdasarkan id dan user_id
        $spend = Spend::where('id', $id)->where('user_id', $user_id)->first();

        // Periksa apakah data spend ditemukan
        if (!$spend) {
            return redirect('/')->with('error', 'Data Spend not found or you do not have access.');
        }

        return view('spend.edit', compact('spend'));
    }

    public function update(Request $request)
    {
        $user_id = Auth::id();

        // Ambil spend berdasarkan id dan user_id
        $spend = Spend::where('id', $request->id)->where('user_id', $user_id)->first();

        // Periksa apakah data spend ditemukan
        if (!$spend) {
            return redirect('/spend')->with('error', 'Data Spend not found or you do not have access.');
        }

        $spend->name = $request->name;
        $spend->amount = $request->amount;
        $spend->save();

        return redirect('/spend')->with('success', 'Data Spend has been updated!');
    }

    public function getSpendData(Request $request)
    {
        $user_id = Auth::id();
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $startDate->addDay();
        $endDate->addDay();
        $end = $endDate->endOfDay();

        Log::info('Fetching spend data', ['start' => $startDate, 'end' => $end]);

        // Ambil spend berdasarkan user_id dan rentang tanggal
        $spend = Spend::where('user_id', $user_id)
            ->whereBetween('created_at', [$startDate, $end])
            ->get();

        return response()->json($spend);
    }

    public function deleteSpendData(Request $request)
    {
        $user_id = Auth::id();
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $startDate->addDay();
        $endDate->addDay();
        $end = $endDate->endOfDay();

        if ($startDate && $end) {
            Spend::where('user_id', $user_id)
                ->whereBetween('created_at', [$startDate, $end])
                ->delete();

            return response()->json(['message' => 'Data pengeluaran berhasil dihapus']);
        }

        return response()->json(['message' => 'Rentang tanggal tidak valid'], 400);
    }

    public function export(Request $request)
    {
        $user_id = Auth::id();
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $startDate->addDay();
        $endDate->addDay();
        $end = $endDate->endOfDay();

        if ($startDate && $endDate) {
            Log::info('Exporting with date range', ['start' => $startDate, 'end' => $end]);
        } else {
            Log::info('Exporting without date range');
        }

        $fileName = 'spends_' . Carbon::now()->englishMonth . '.xlsx';
        return Excel::download(new SpendsExport($user_id, $startDate, $endDate), $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new SpendsImport, $request->file('file'));

        return redirect('/spend')->with('success', 'Spends imported successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
