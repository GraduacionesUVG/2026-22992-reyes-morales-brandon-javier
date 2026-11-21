<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View{
        $query=trim((string)$request->input('q',''));
        $courses=collect();
        $modules=collect();
        $lessons=collect();

        if(strlen($query)>=2){
            $term='%'.mb_strtolower($query,'UTF-8').'%';

            $courses=Course::published()
                ->where(function($q) use($term){
                    $q->whereRaw('LOWER(title) LIKE ?',[$term])
                      ->orWhereRaw('LOWER(description) LIKE ?',[$term]);
                })
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(8)
                ->get();

            $modules=Module::with('course')
                ->published()
                ->whereHas('course',fn($q)=>$q->published())
                ->where(function($q) use($term){
                    $q->whereRaw('LOWER(title) LIKE ?',[$term])
                      ->orWhereRaw('LOWER(description) LIKE ?',[$term]);
                })
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(8)
                ->get();

            $lessons=Lesson::with('module.course')
                ->published()
                ->whereHas('module',fn($q)=>$q->published()->whereHas('course',fn($q)=>$q->published()))
                ->where(function($q) use($term){
                    $q->whereRaw('LOWER(title) LIKE ?',[$term])
                      ->orWhereRaw('LOWER(summary) LIKE ?',[$term])
                      ->orWhereRaw('LOWER(content) LIKE ?',[$term]);
                })
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(12)
                ->get();
        }

        return view('student.search.index',[
            'query'=>$query,
            'courses'=>$courses,
            'modules'=>$modules,
            'lessons'=>$lessons,
            'hasSearched'=>strlen($query)>=2,
        ]);
    }
}