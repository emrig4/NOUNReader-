<?php

namespace App\Http\Controllers;

use App\Models\PlagiarismCheck;
use App\Models\UserPlagiarismLimit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlagiarismCheckerController extends Controller
{
    /**
     * Display the plagiarism checker interface.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create today's limits
        $limits = $this->getUserLimits($user);
        
        return view('plagiarism-checker.index', compact('user', 'limits'));
    }

    /**
     * Perform plagiarism check.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|min:50|max:10000',
            'file' => 'nullable|file|max:10240|mimes:txt,pdf,doc,docx'
        ]);

        $text = $request->input('text', '');
        $wordCount = str_word_count(strip_tags($text));
        
        // Check word limit
        if ($wordCount > 1000) {
            return response()->json([
                'success' => false,
                'message' => 'Text exceeds the 1,000 word limit for free checks. Please shorten your text or upgrade to premium.'
            ], 400);
        }

        $user = Auth::user();
        $limits = $this->getUserLimits($user);

        // Check daily limits
        if (!$limits->canPerformCheck($wordCount)) {
            return response()->json([
                'success' => false,
                'message' => 'Daily limit exceeded. Please try again tomorrow or upgrade to premium.',
                'limits' => [
                    'remaining_checks' => $limits->remaining_checks,
                    'remaining_words' => $limits->remaining_words,
                ]
            ], 429);
        }

        // Perform plagiarism check
        $startTime = microtime(true);
        $result = $this->performPlagiarismCheck($text);
        $checkTime = round(microtime(true) - $startTime, 2);

        // Save the check
        $plagiarismCheck = PlagiarismCheck::create([
            'user_id' => $user ? $user->id : null,
            'original_text' => $text,
            'word_count' => $wordCount,
            'plagiarism_score' => $result['score'],
            'sources' => $result['sources'],
            'status' => 'completed',
            'check_time' => $checkTime,
            'session_id' => $sessionId ?? Str::uuid(),
        ]);

        // Update usage
        $limits->incrementUsage(1, $wordCount);

        return response()->json([
            'success' => true,
            'data' => [
                'check_id' => $plagiarismCheck->id,
                'score' => $result['score'],
                'sources' => $result['sources'],
                'word_count' => $wordCount,
                'check_time' => $checkTime,
                'usage' => [
                    'remaining_checks' => $limits->remaining_checks,
                    'remaining_words' => $limits->remaining_words,
                ]
            ]
        ]);

    }

    /**
     * Get check history for user.
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $checks = PlagiarismCheck::forUser($user ? $user->id : null)
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('plagiarism-checker.history', compact('checks'));
    }

    /**
     * Download report as PDF.
     */
    public function downloadReport($checkId)
    {
        $user = Auth::user();
        $check = PlagiarismCheck::forUser($user ? $user->id : null)
            ->findOrFail($checkId);

        // Generate simple PDF report (placeholder)
        $pdfContent = "Plagiarism Check Report\n\n";
        $pdfContent .= "Check ID: {$check->id}\n";
        $pdfContent .= "Date: {$check->created_at->format('Y-m-d H:i:s')}\n";
        $pdfContent .= "Word Count: {$check->word_count}\n";
        $pdfContent .= "Plagiarism Score: {$check->plagiarism_score}%\n";
        $pdfContent .= "Status: {$check->status}\n";

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="plagiarism-report-' . $check->id . '.pdf"');
    }

    /**
     * Get user limits for today.
     */
    private function getUserLimits($user)
    {
        $today = now()->toDateString();
        
        $limits = UserPlagiarismLimit::where('user_id', $user ? $user->id : null)
            ->where('check_date', $today)
            ->first();

        if (!$limits) {
            $limits = UserPlagiarismLimit::create([
                'user_id' => $user ? $user->id : null,
                'check_date' => $today,
                'checks_used' => 0,
                'words_used' => 0,
            ]);
        }

        return $limits;
    }

    private function getUserLimitConfig($user)
    {
        // This is the free version - all users get the same limits
        // Premium features will be added in future versions
        return [
            'daily_checks' => $user ? 20 : 5,
            'daily_words' => 1000,
        ];
    }

    /**
     * Perform actual plagiarism check simulation.
     */
    private function performPlagiarismCheck($text)
    {
        // This is a simulation for demo purposes
        // In production, integrate with services like:
        // - Copyscape API
        // - Turnitin API
        // - Grammarly Plagiarism Checker

        $wordCount = str_word_count(strip_tags($text));
        
        // Simulate realistic plagiarism score
        $score = rand(0, 35); // Keep it low for academic content
        
        // Generate sample sources
        $sources = [];
        if ($score > 5) {
            $sampleSources = [
                ['url' => 'https://example.com/source1', 'title' => 'Academic Journal Article', 'match' => rand(5, 15)],
                ['url' => 'https://example.com/source2', 'title' => 'Research Paper', 'match' => rand(3, 12)],
                ['url' => 'https://example.com/source3', 'title' => 'Educational Website', 'match' => rand(2, 10)],
            ];
            $sources = array_slice($sampleSources, 0, rand(1, min(3, count($sampleSources))));
        }

        return [
            'score' => $score,
            'sources' => $sources,
            'word_count' => $wordCount,
            'status' => 'completed'
        ];
    }
}
