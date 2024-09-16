<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryEntry extends Model
{
    use HasFactory;
    protected $table = 'diary_entries'; 
    protected $fillable = ['user_id', 'date', 'content']; 
    protected $casts = [ 
    'date' => 'datetime', 
    'created_at' => 'datetime', 
    'updated_at' => 'datetime', 
    ];

    public function user()
    { 
   return $this->belongsTo(User::class); 
   }

   public function emotions()
   { 
  return $this->belongsToMany(Emotion::class, 'diary_entry_emotions', 
  'diary_entry_id', 'emotion_id') 
                  ->withPivot('intensity') 
                  ->withTimestamps(); 
  } 
 
  public function show($id)
{
    $diaryEntry = DiaryEntry::find($id);

    // Pass the diary entry and its emotions to the view
    return view('diary.show', compact('diaryEntry'));
}

public function conflictingEmotions()
{
    $userId = Auth::id(); // Get the authenticated user's ID

    // Fetch diary entries where the emotion is "Sad" (emotion_id = 2) and content contains "happy"
    $entries = DB::table('diary_entries as de')
        ->join('diary_entry_emotions as dee', 'de.id', '=', 'dee.diary_entry_id')
        ->where('de.user_id', $userId)
        ->where('dee.emotion_id', 2)
        ->where('de.content', 'LIKE', '%happy%')
        ->select('de.id', 'de.date', 'de.content')
        ->get();

    // Log the query for debugging
    \Log::info('Conflicting emotions query:', [
        'query' => DB::getQueryLog(),
        'entries' => $entries
    ]);

    // Return the entries as a JSON response
    return response()->json($entries);
}


 
}
