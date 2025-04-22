<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function index(){
        return view('skills');
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'skills' => 'required',
            ]);
    
            $skills = json_decode($request->skills);
            

            \Log::info('Skills received:', ['skills' => $skills]);
    
            foreach ($skills as $skillName) {
                Skills::firstOrCreate(['name' => $skillName]);
            }
            
            return response()->json(['success' => true, 'message' => 'Skills saved successfully']);
        } catch (\Exception $e) {
            \Log::error('Error saving skills: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
}
