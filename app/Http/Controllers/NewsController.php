<?php

namespace App\Http\Controllers;
use App\Models\NewsModel;
use App\Models\GuardianModel;
use App\Models\NYTimesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function fetchData(Request $request){
        $bbcNews = NewsModel::where('source_name', 'BBC News')->orderBy('published_at', 'DESC')->get();
        $techCrunchAndNextWeb = NewsModel::whereIn('source_name', ['TechCrunch', 'The Next Web'])->orderBy('published_at', 'DESC')->get();

        return response()->json([
            'bbcNews' => $bbcNews,
            'techCrunchAndNextWeb' => $techCrunchAndNextWeb,
        ]);
    }

    public function fetchAllNews(Request $request){
        $newsData = NewsModel::all();
        $guardianData = GuardianModel::all();
        $nytimesData = NYTimesModel::all();
        // Combine the data into one array with keys representing the model names
        $combinedData = [
            'news' => $newsData,
            'guardian' => $guardianData,
            'nytimes' => $nytimesData,
        ];

        // Return the combined data
        return response()->json($combinedData);
    }

    public function fetchNewsArticle(Request $request){
        return NewsModel::orderBy('published_date', 'DESC')->get();
    }

    public function fetchGuardianData(Request $request){
        return GuardianModel::orderBy('webPublicationDate', 'DESC')->get();
    }

    public function fetchNYTimesData(Request $request){
        return NYTimesModel::orderBy('published_date', 'DESC')->get();
    }
    
    public function getSources()
    {
        $table1Sources = DB::table('news_articles')->select('source_name')->whereNotNull('source_name')->where('source_name', '!=', '')->get();
        $table2Sources = DB::table('guardian_news')->select('sectionName AS source_name')->whereNotNull('sectionName')->where('sectionName', '!=', '')->get();
        $table3Sources = DB::table('nytimes_news')->select('subsection AS source_name')->whereNotNull('subsection')->where('subsection', '!=', '')->get();

        $sources = $table1Sources->merge($table2Sources)->merge($table3Sources)->unique()->values();

        return response()->json([
            'status' => 'success',
            'data' => $sources,
        ]);
    }

    public function getCategory()
    {
        $table2Category = DB::table('guardian_news')->select('type AS category')->whereNotNull('type')->where('type', '!=', '')->get();
        $table3Category = DB::table('nytimes_news')
                                ->select('item_type AS category')
                                ->whereNotNull('item_type')
                                ->where('item_type', '!=', '')
                                ->union(
                                    DB::table('nytimes_news')
                                        ->select('section AS category')
                                        ->whereNotNull('section')
                                        ->where('section', '!=', '')
                                )
                                ->get();

        $categories = $table2Category->merge($table3Category)->unique()->values();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function getAuthors()
    {
        $table1Authors = DB::table('news_articles')->select('author AS authorName')->whereNotNull('author')->where('author', '!=', '')->get();
        $table2Authors = DB::table('nytimes_news')->select('byline AS authorName')->whereNotNull('byline')->where('byline', '!=', '')->get();


        $authors = $table1Authors->merge($table2Authors)->unique()->values();

        return response()->json([
            'status' => 'success',
            'data' => $authors,
        ]);
    }

    public function filterNews(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string'
        ]);
    
        $source = $validated['source'];
        $filteredNews = GuardianModel::where('sectionName', $source)->get();
        if ($filteredNews->isEmpty()) {
            $filteredNews = NYTimesModel::where('subsection', $source)->get();
            if (!$filteredNews->isEmpty()) {
                return response()->json([
                    'nytimes' => $filteredNews
                ]);
            } else {
                $filteredNews = NewsModel::where('source_name', $source)->get();
                if (!$filteredNews->isEmpty()) {
                    return response()->json([
                        'news' => $filteredNews
                    ]);
                } else {
                    return response()->json([
                        'message' => 'No news found for the selected source.'
                    ]);
                }
            }
        }
    
        // If results found in GuardianModel, return Guardian data
        return response()->json([
            'guardian' => $filteredNews
        ]);
    }
    
    public function filterNewsByCategory(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string'
        ]);
    
        $category = $validated['category'];
        $filteredNews = GuardianModel::where('type', $category)->get();
        if ($filteredNews->isEmpty()) {
            $filteredNews = NYTimesModel::where('item_type', $category)
            ->union(
                NYTimesModel::where('section', $category)
            )
            ->get();
            if (!$filteredNews->isEmpty()) {
                return response()->json([
                    'nytimes' => $filteredNews
                ]);
            } else {
                    return response()->json([
                        'message' => 'No news found for the selected category.'
                    ]);
            }
        }
    
        // If results found in GuardianModel, return Guardian data
        return response()->json([
            'guardian' => $filteredNews
        ]);
    }

    public function filterNewsByAuthor(Request $request)
    {
        $table1Authors = DB::table('news_articles')->select('author AS authorName')->whereNotNull('author')->where('author', '!=', '')->get();
        $table2Authors = DB::table('nytimes_news')->select('byline AS authorName')->whereNotNull('byline')->where('byline', '!=', '')->get();

        $validated = $request->validate([
            'author' => 'required|string'
        ]);
    
        $author = $validated['author'];
        $filteredNews = NewsModel::where('author', $author)->get();
        if ($filteredNews->isEmpty()) {
            $filteredNews = NYTimesModel::where('byline', $author)->get();
            if (!$filteredNews->isEmpty()) {
                return response()->json([
                    'nytimes' => $filteredNews
                ]);
            } else {
                    return response()->json([
                        'message' => 'No news found for the selected category.'
                    ]);
            }
        }
    
        // If results found in GuardianModel, return Guardian data
        return response()->json([
            'news' => $filteredNews
        ]);
    }
}
