<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\TTElements;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TTElementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ttelements = TTElements::all();

        return response()->json($ttelements, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ttelements = new TTElements();
        $ttelements->fill($request->only([
            'ttid', 
            'day', 
            'title',
            'description',
            'start',
            'end',
            'repeating']));
        $desc = $request->only(['description']);
        if ($desc['description'] == null) $ttelements->fill(['description' => '']);
        $ttelements->save();
        return response()->json($ttelements, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TTElements  $tTElements
     * @return \Illuminate\Http\Response
     */
    public function show(int $tTElements)
    {
        return TTElements::findOrFail($tTElements);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TTElements  $tTElements
     * @return \Illuminate\Http\Response
     */
    public function edit(TTElements $tTElements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TTElements  $tTElements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tTElements = TTElements::findOrFail($id);

        $tTElements->fill($request->only([
            'ttid', 
            'day', 
            'title',
            'description',
            'start',
            'end',
            'repeating']));
        $desc = $request->only(['description']);
        if ($desc['description'] == null) $tTElements->fill(['description' => '']);
        $tTElements->save();
        return response()->json($tTElements, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TTElements  $tTElements
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tTElements = TTElements::findOrFail($id);
        return response()->json($tTElements->delete(), 200);
    }

    public function getAllElements($tableId) 
    {
        //$this->deleteOutdatedTTElements($tableId);

        $elements = TTElements::where('ttid', $tableId)->get()->toArray();
        return response()->json($elements, 200);
    }

    public function getFullTimetables(int $userId) {
        $ttids = Timetable::all()->where('userId', $userId)->pluck('id');

        $this->deleteOutdatedTTElements($ttids);

        $timeTableElementList = TTElements::whereIn('ttid', $ttids)->get()->toArray();
        return response()->json($timeTableElementList, 200);
    }

    private function deleteOutdatedTTElements($tableId) {
        if ($tableId->count() > 1) $ttelements = TTElements::whereIn('ttid', $tableId)->get();
        else $ttelements = TTElements::where('ttid', $tableId)->get();
        
        foreach ($ttelements as $tte) {
            $date = Carbon::parse($tte->created_at)->next($tte->day);
            if(!$tte->repeating && $date < today()->subDay()) {
                $tte->delete();
            }
        };
    }
}
