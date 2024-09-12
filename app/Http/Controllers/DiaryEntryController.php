<?php

namespace App\Http\Controllers;
use App\Models\DiaryEntry; //เพิ่มบรรทัดนี้
use Illuminate\Support\Facades\Auth; //เพิ่มบรรทัดนี้
use Illuminate\Http\Request;
use App\Models\Emotion;
use Illuminate\Support\Facades\DB;

class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Get the paginated diary entries with their associated emotions
       $diaryEntries = Auth::user()->diaryEntries()->with('emotions')->paginate(2);
       // Get the logged-in user ID
       $userId = Auth::id();
       // Count how many diaries are related to each emotion 
       $emotionCounts = DB::table('diary_entry_emotions as dee')
           ->join('diary_entries as de', 'dee.diary_entry_id', '=', 'de.id')
           ->select('dee.emotion_id', DB::raw('count(dee.diary_entry_id) as diary_count'))
           ->where('de.user_id', $userId)
           ->whereIn('dee.emotion_id', [1, 2, 3, 4, 5]) 
           ->groupBy('dee.emotion_id')
           ->get();
       // Convert the data into a format suitable for display
       $summary = [];
       foreach ($emotionCounts as $count) {
           $summary[$count->emotion_id] = $count->diary_count;
       }
       // Return the view with both diary entries and summary data
       return view('diary.index', compact('diaryEntries', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $emotions = Emotion::all(); // Fetch all emotions for selection 
        return view('diary.create', compact('emotions')); // Pass emotions to the view 
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //         $diaryEntry = Auth::user()->diaryEntries()->create([ 
    //             'date' => $validated['date'], 
    //             'content' => $validated['content'], 
    //                 ]); 
    
    //                 if (!empty($validated['emotions']) && 
    //                 !empty($validated['intensity'])) { 
    //                 foreach ($validated['emotions'] as $emotionId) { 
    //                             $intensity = $validated['intensity'][$emotionId] ?? null; 
    //                 // Attach emotions and intensities to the diary entry 
    //                             $diaryEntry->emotions()->attach($emotionId, ['intensity' => 
    //                 $intensity]); 
    //                         } 
    //                     } 
    //                 return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!'); 
    // }

    public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'content' => 'required|string',
        'emotions' => 'sometimes|array', // Validate emotions as an array
        'intensity' => 'sometimes|array', // Validate intensity as an array
    ]);

    // Create a new diary entry
    $diaryEntry = Auth::user()->diaryEntries()->create([
        'date' => $validated['date'],
        'content' => $validated['content'],
    ]);

    // Attach emotions and intensities if they are provided
    if (!empty($validated['emotions']) && !empty($validated['intensity'])) {
        foreach ($validated['emotions'] as $emotionId) {
            $intensity = $validated['intensity'][$emotionId] ?? null;
            $diaryEntry->emotions()->attach($emotionId, ['intensity' => $intensity]);
        }
    }

    return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id); 
        return view('diary.show', compact('diaryEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diaryEntry = Auth::user()->diaryEntries()->with('emotions')->findOrFail($id); 
        $emotions = Emotion::all(); // you must have a model called Emotion to fetch all emotions 
        return view('diary.edit', compact('diaryEntry', 'emotions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        // Validate the request 
            $validated = $request->validate([ 
            'date' => 'required|date', 
            'content' => 'required|string', 
            'emotions' => 'array', // Validate emotions as an array 
            'intensity' => 'array', // Validate intensity as an array 
            ]); 
        // Find and update the diary entry 
            $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id); 
            $diaryEntry->update([ 
            'date' => $validated['date'], 
            'content' => $validated['content'], 
            ]); 
        // Sync emotions and intensities 
            if (!empty($validated['emotions'])) { 
                $emotions = []; 
                foreach ($validated['emotions'] as $emotionId) { 
                    $intensity = $validated['intensity'][$emotionId] ?? null; 
                    $emotions[$emotionId] = ['intensity' => $intensity]; 
                } 
                $diaryEntry->emotions()->sync($emotions); 
            } 
            else { 
        // If no emotions are selected, clear all associated emotions 
                $diaryEntry->emotions()->sync([]); 
            } 
        return redirect()->route('diary.index')->with('status', 'Diary entry updated successfully!'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Retrieve the diary entry by its ID 
        $diaryEntry = DiaryEntry::findOrFail($id); 
        // Delete the retrieved diary entry 
        $diaryEntry->delete(); 
        // Redirect back to the diary index with a success message 
        return redirect()->route('diary.index')->with('status', 'Diary entry deleted successfully!'); 
    }

    public function display_diary()
    {
       $userId = Auth::id(); // Get the authenticated user's ID
       // Fetch all diary entries for the authenticated user
       $diaryEntries = DB::table('diary_entries')
           ->where('user_id', $userId)
            ->get();  //ได้ List diary user 
           //->first(); // get diary อันล่าสุดออกมา
       return view('diary.display_diary', compact('diaryEntries')); //ได้ List diary user
       //return response()->json($diaryEntries); // ออกมาเป็น  json เเทน

    }

    public function diary_count()
    {
       $userId = Auth::id();
       $diary_count = DB::table('diary_entries')
           ->select('date', DB::raw('count(*) as count'))
           ->where('user_id', $userId)
           ->groupBy('date')
           ->having('count', '>=', 2)
           ->get();
       return response()->json(['diary_count' => $diary_count]);
    }

    // *********************** เอามาเเต่เนื้อหา diary *******************************
    
    // public function display_diary()
    // {
    //     $userId = Auth::id(); // Get the authenticated user's ID
    //     $contents = DB::table('diary_entries')
    //         ->where('user_id', $userId)
    //         ->pluck('content');
        
    //     return response()->json($contents);
    // }  

    // ******************************************************

    public function count_happy_diary()
    {
       $userId = Auth::id();
       $happyEmotionCount = DB::table('users as u')
           ->join('diary_entries as de', 'u.id', '=', 'de.user_id')
           ->join('diary_entry_emotions as dee', 'de.id', '=', 'dee.diary_entry_id')
           ->where('u.id', $userId)
           ->where('dee.emotion_id', 1)
           ->count('de.id');
       return response()->json(['happyEmotionCount' => $happyEmotionCount]);
    }
}
