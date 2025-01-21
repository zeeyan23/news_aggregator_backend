<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadLaterModel;

class ReadLaterController extends Controller
{
    public function saveReadLater(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'news_id' => 'required',
            'user_id' => 'required|integer|exists:users,id',
            'news_type' => 'required|string', // Restrict to valid models
        ]);

        try {
            // Save to read_later table
            $readLater = ReadLaterModel::create([
                'news_id' => $validated['news_id'],
                'user_id' => $validated['user_id'],
                'news_type' => $validated['news_type'], // Store the news model type
            ]);

            return response()->json([
                'message' => 'News saved to read later.',
                'data' => $readLater
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving to Read Later',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSavedReadLater(Request $request) {
        $userId = $request->input('user_id');
        $readLaterItems = ReadLaterModel::where('user_id', $userId)->get();
        return response()->json($readLaterItems);
    }


    public function getSavedArticles(Request $request)
    {
        $userId = $request->input('user_id');
        
        // Fetch Read Later items with the related news details
        $readLaterItems = ReadLaterModel::with('news')
            ->where('user_id', $userId)
            ->get();

        return response()->json($readLaterItems);
    }

}
