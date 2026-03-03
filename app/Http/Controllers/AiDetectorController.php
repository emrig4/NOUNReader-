<?php

namespace App\Http\Controllers;

use App\Models\AiDetection;
use App\Models\UserAiDetectionLimit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiDetectorController extends Controller
{
    /**
     * Display the AI detector interface.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create today's limits
        $limits = $this->getUserLimits($user);
        
        return view('ai-detector.index', compact('user', 'limits'));
    }

    /**
     * Perform AI detection.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|min:50|max:15000',
            'file' => 'nullable|file|max:10240|mimes:txt,pdf,doc,docx'
        ]);

        $text = $request->input('text', '');
        $wordCount = str_word_count(strip_tags($text));
        
        // Check word limit
        if ($wordCount > 1500) {
            return response()->json([
                'success' => false,
                'message' => 'Text exceeds the 1,500 word limit for free checks. Please shorten your text or upgrade to premium.'
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

        // Perform AI detection
        $startTime = microtime(true);
        $result = $this->performAiDetection($text);
        $detectionTime = round(microtime(true) - $startTime, 3);

        // Save the detection
        $aiDetection = AiDetection::create([
            'user_id' => $user ? $user->id : null,
            'original_text' => $text,
            'word_count' => $wordCount,
            'ai_score' => $result['score'],
            'confidence_level' => $result['confidence_level'],
            'indicators' => $result['indicators'],
            'writing_style' => $result['writing_style'],
            'status' => 'completed',
            'detection_time' => $detectionTime,
            'session_id' => session()->getId(),
        ]);

        // Update usage
        $limits->incrementUsage(1, $wordCount);

        return response()->json([
            'success' => true,
            'data' => [
                'detection_id' => $aiDetection->id,
                'score' => $result['score'],
                'confidence_level' => $result['confidence_level'],
                'indicators' => $result['indicators'],
                'writing_style' => $result['writing_style'],
                'word_count' => $wordCount,
                'detection_time' => $detectionTime,
                'usage' => [
                    'remaining_checks' => $limits->remaining_checks,
                    'remaining_words' => $limits->remaining_words,
                ]
            ]
        ]);
    }

    /**
     * Get detection history for user.
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $detections = AiDetection::forUser($user ? $user->id : null)
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ai-detector.history', compact('detections'));
    }

    /**
     * Download detection report as PDF.
     */
    public function downloadReport($detectionId)
    {
        $user = Auth::user();
        $detection = AiDetection::forUser($user ? $user->id : null)
            ->findOrFail($detectionId);

        // Generate simple PDF report
        $pdfContent = "AI Detection Report\n\n";
        $pdfContent .= "Detection ID: {$detection->id}\n";
        $pdfContent .= "Date: {$detection->created_at->format('Y-m-d H:i:s')}\n";
        $pdfContent .= "Word Count: {$detection->word_count}\n";
        $pdfContent .= "AI Score: {$detection->ai_score}%\n";
        $pdfContent .= "Confidence Level: {$detection->confidence_level}\n";
        $pdfContent .= "Detection Result: {$detection->detection_result}\n";
        $pdfContent .= "Indicators: {$detection->indicators_summary}\n";
        $pdfContent .= "Writing Style: {$detection->style_summary}\n";
        $pdfContent .= "Status: {$detection->status}\n";

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="ai-detection-report-' . $detection->id . '.pdf"');
    }

    /**
     * API endpoint for AJAX detection.
     */
    public function apiCheck(Request $request): JsonResponse
    {
        return $this->check($request);
    }

    /**
     * API endpoint for detection history.
     */
    public function apiHistory(Request $request): JsonResponse
    {
        $user = Auth::user();
        $detections = AiDetection::forUser($user ? $user->id : null)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $detections->map(function ($detection) {
                return [
                    'id' => $detection->id,
                    'word_count' => $detection->word_count,
                    'ai_score' => $detection->ai_score,
                    'confidence_level' => $detection->confidence_level,
                    'detection_result' => $detection->detection_result,
                    'created_at' => $detection->created_at->toISOString(),
                ];
            })
        ]);
    }

    /**
     * API endpoint for detection statistics.
     */
    public function apiStats(Request $request): JsonResponse
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;

        $stats = [
            'total_detections' => AiDetection::forUser($userId)->count(),
            'today_detections' => AiDetection::forUser($userId)->whereDate('created_at', today())->count(),
            'avg_ai_score' => AiDetection::forUser($userId)->avg('ai_score'),
            'ai_detections' => AiDetection::forUser($userId)->where('ai_score', '>=', 70)->count(),
            'human_detections' => AiDetection::forUser($userId)->where('ai_score', '<', 40)->count(),
            'uncertain_detections' => AiDetection::forUser($userId)->whereBetween('ai_score', [40, 69])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Public API endpoint for detection (for external integrations).
     */
    public function publicDetect(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|min:50|max:10000',
            'api_key' => 'required|string'
        ]);

        // Validate API key (implement your API key validation logic)
        if (!$this->validateApiKey($request->api_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        $text = $request->input('text');
        $wordCount = str_word_count(strip_tags($text));
        
        if ($wordCount > 10000) {
            return response()->json([
                'success' => false,
                'message' => 'Text exceeds maximum word limit'
            ], 400);
        }

        // Perform detection without user association
        $startTime = microtime(true);
        $result = $this->performAiDetection($text);
        $detectionTime = round(microtime(true) - $startTime, 3);

        // Save anonymous detection
        $aiDetection = AiDetection::create([
            'original_text' => $text,
            'word_count' => $wordCount,
            'ai_score' => $result['score'],
            'confidence_level' => $result['confidence_level'],
            'indicators' => $result['indicators'],
            'writing_style' => $result['writing_style'],
            'status' => 'completed',
            'detection_time' => $detectionTime,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'detection_id' => $aiDetection->id,
                'score' => $result['score'],
                'confidence_level' => $result['confidence_level'],
                'detection_result' => $result['score'] >= 70 ? 'Likely AI Generated' : ($result['score'] >= 40 ? 'Uncertain' : 'Likely Human Written'),
                'word_count' => $wordCount,
                'detection_time' => $detectionTime,
                'indicators' => $result['indicators'],
                'writing_style' => $result['writing_style'],
            ]
        ]);
    }

    /**
     * Get public detection by ID.
     */
    public function publicGetDetection(Request $request, $id): JsonResponse
    {
        $detection = AiDetection::find($id);
        
        if (!$detection) {
            return response()->json([
                'success' => false,
                'message' => 'Detection not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $detection->id,
                'word_count' => $detection->word_count,
                'ai_score' => $detection->ai_score,
                'confidence_level' => $detection->confidence_level,
                'detection_result' => $detection->detection_result,
                'created_at' => $detection->created_at->toISOString(),
            ]
        ]);
    }

    /**
     * Get user limits for today.
     */
    private function getUserLimits($user)
    {
        $today = now()->toDateString();
        
        $limits = UserAiDetectionLimit::where('user_id', $user ? $user->id : null)
            ->where('check_date', $today)
            ->first();

        if (!$limits) {
            $limits = UserAiDetectionLimit::create([
                'user_id' => $user ? $user->id : null,
                'check_date' => $today,
                'checks_used' => 0,
                'words_used' => 0,
            ]);
        }

        return $limits;
    }

    /**
     * Validate API key (implement your logic here).
     */
    private function validateApiKey(string $apiKey): bool
    {
        // This is a placeholder - implement your API key validation logic
        // For example, check against a database table of valid API keys
        return true;
    }

    /**
     * Perform AI detection analysis.
     */
    private function performAiDetection(string $text): array
    {
        $analysis = $this->analyzeText($text);
        
        // Calculate AI confidence score based on multiple factors
        $score = $this->calculateAiScore($analysis);
        
        // Determine confidence level
        $confidenceLevel = $this->determineConfidenceLevel($analysis);
        
        return [
            'score' => $score,
            'confidence_level' => $confidenceLevel,
            'indicators' => $analysis['indicators'],
            'writing_style' => $analysis['writing_style'],
            'status' => 'completed'
        ];
    }

    /**
     * Analyze text for AI indicators.
     */
    private function analyzeText(string $text): array
    {
        // Clean and prepare text
        $cleanText = strip_tags($text);
        $sentences = preg_split('/[.!?]+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);
        $words = preg_split('/\s+/', $cleanText);
        
        // Remove empty words
        $words = array_filter($words, function($word) {
            return strlen(trim($word)) > 0;
        });

        $analysis = [
            'indicators' => [],
            'writing_style' => [],
            'metrics' => []
        ];

        // 1. Vocabulary Complexity Analysis
        $complexityAnalysis = $this->analyzeVocabularyComplexity($words);
        $analysis['indicators']['complexity'] = $complexityAnalysis;
        
        // 2. Sentence Structure Analysis
        $structureAnalysis = $this->analyzeSentenceStructure($sentences);
        $analysis['indicators']['structure'] = $structureAnalysis;
        
        // 3. Repetitive Pattern Detection
        $repetitionAnalysis = $this->analyzeRepetitivePatterns($words);
        $analysis['indicators']['repetition'] = $repetitionAnalysis;
        
        // 4. Writing Style Analysis
        $styleAnalysis = $this->analyzeWritingStyle($cleanText, $words, $sentences);
        $analysis['writing_style'] = $styleAnalysis;

        // 5. Semantic Coherence Analysis
        $coherenceAnalysis = $this->analyzeSemanticCoherence($sentences);
        $analysis['indicators']['coherence'] = $coherenceAnalysis;

        // 6. Common AI Phrases Detection
        $phraseAnalysis = $this->analyzeCommonPhrases($cleanText);
        $analysis['indicators']['phrases'] = $phraseAnalysis;

        return $analysis;
    }

    /**
     * Analyze vocabulary complexity.
     */
    private function analyzeVocabularyComplexity(array $words): array
    {
        $complexWords = 0;
        $totalWords = count($words);
        
        foreach ($words as $word) {
            $cleanWord = strtolower(preg_replace('/[^a-zA-Z]/', '', $word));
            if (strlen($cleanWord) > 7) {
                $complexWords++;
            }
        }
        
        $complexityRatio = $totalWords > 0 ? $complexWords / $totalWords : 0;
        
        // Check for unusual vocabulary patterns
        $unusualWords = 0;
        $commonWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should'];
        
        foreach ($words as $word) {
            if (!in_array(strtolower($word), $commonWords) && !is_numeric($word)) {
                $unusualWords++;
            }
        }
        
        $unusualRatio = $totalWords > 0 ? $unusualWords / $totalWords : 0;
        
        return [
            'score' => min(1.0, ($complexityRatio * 2 + $unusualRatio) / 3),
            'complexity_ratio' => $complexityRatio,
            'unusual_ratio' => $unusualRatio,
            'total_words' => $totalWords,
            'complex_words' => $complexWords,
        ];
    }

    /**
     * Analyze sentence structure.
     */
    private function analyzeSentenceStructure(array $sentences): array
    {
        $sentenceLengths = array_map('str_word_count', $sentences);
        $avgLength = count($sentenceLengths) > 0 ? array_sum($sentenceLengths) / count($sentenceLengths) : 0;
        
        // Check for consistent sentence lengths (AI indicator)
        $variance = 0;
        if (count($sentenceLengths) > 1) {
            $mean = $avgLength;
            $variance = array_sum(array_map(function($length) use ($mean) {
                return pow($length - $mean, 2);
            }, $sentenceLengths)) / count($sentenceLengths);
        }
        
        // Check for complex sentence structures
        $complexSentences = 0;
        foreach ($sentences as $sentence) {
            $commas = substr_count($sentence, ',');
            $semicolons = substr_count($sentence, ';');
            $colons = substr_count($sentence, ':');
            if ($commas + $semicolons + $colons > 2) {
                $complexSentences++;
            }
        }
        
        $complexityRatio = count($sentences) > 0 ? $complexSentences / count($sentences) : 0;
        
        return [
            'score' => min(1.0, ($variance / 100 + $complexityRatio) / 2),
            'avg_sentence_length' => round($avgLength, 2),
            'sentence_variance' => round($variance, 2),
            'complexity_ratio' => $complexityRatio,
            'total_sentences' => count($sentences),
        ];
    }

    /**
     * Analyze repetitive patterns.
     */
    private function analyzeRepetitivePatterns(array $words): array
    {
        $wordFreq = array_count_values(array_map('strtolower', $words));
        $totalWords = count($words);
        
        // Calculate word frequency distribution
        $frequencies = array_values($wordFreq);
        $uniqueWords = count($wordFreq);
        
        // Type-Token Ratio (lower ratio indicates more repetition)
        $typeTokenRatio = $totalWords > 0 ? $uniqueWords / $totalWords : 0;
        
        // Check for repetitive phrases
        $repetitiveScore = 0;
        if ($typeTokenRatio < 0.5) {
            $repetitiveScore += 0.3;
        }
        
        // Check for common AI transition phrases
        $aiPhrases = ['furthermore', 'moreover', 'additionally', 'consequently', 'therefore', 'in conclusion', 'in summary', 'it is important to note', 'it should be noted'];
        $phraseCount = 0;
        foreach ($aiPhrases as $phrase) {
            if (stripos(implode(' ', $words), $phrase) !== false) {
                $phraseCount++;
            }
        }
        
        if ($phraseCount > 0) {
            $repetitiveScore += min(0.4, $phraseCount * 0.1);
        }
        
        return [
            'score' => min(1.0, $repetitiveScore),
            'type_token_ratio' => round($typeTokenRatio, 3),
            'unique_words' => $uniqueWords,
            'total_words' => $totalWords,
            'ai_phrases_found' => $phraseCount,
        ];
    }

    /**
     * Analyze writing style.
     */
    private function analyzeWritingStyle(string $text, array $words, array $sentences): array
    {
        $totalWords = count($words);
        $totalSentences = count($sentences);
        
        // Vocabulary diversity (unique words / total words)
        $uniqueWords = count(array_unique(array_map('strtolower', $words)));
        $vocabularyDiversity = $totalWords > 0 ? $uniqueWords / $totalWords : 0;
        
        // Average sentence length
        $avgSentenceLength = $totalSentences > 0 ? $totalWords / $totalSentences : 0;
        
        // Check for formal language patterns
        $formalWords = ['utilize', 'demonstrate', 'facilitate', 'implement', 'indicate', 'suggest', 'require', 'establish', 'maintain', 'ensure'];
        $formalWordCount = 0;
        foreach ($formalWords as $formalWord) {
            $formalWordCount += substr_count(strtolower($text), $formalWord);
        }
        
        $formalRatio = $totalWords > 0 ? $formalWordCount / $totalWords : 0;
        
        // Check for passive voice patterns
        $passivePatterns = ['is being', 'was being', 'are being', 'were being', 'has been', 'have been', 'had been', 'will be'];
        $passiveCount = 0;
        foreach ($passivePatterns as $pattern) {
            $passiveCount += substr_count(strtolower($text), $pattern);
        }
        
        $passiveRatio = $totalSentences > 0 ? $passiveCount / $totalSentences : 0;
        
        return [
            'vocabulary_diversity' => round($vocabularyDiversity, 3),
            'avg_sentence_length' => round($avgSentenceLength, 2),
            'formal_language_ratio' => round($formalRatio, 3),
            'passive_voice_ratio' => round($passiveRatio, 3),
            'unique_words' => $uniqueWords,
            'total_words' => $totalWords,
            'total_sentences' => $totalSentences,
        ];
    }

    /**
     * Analyze semantic coherence.
     */
    private function analyzeSemanticCoherence(array $sentences): array
    {
        if (count($sentences) < 3) {
            return ['score' => 0.5, 'coherence_level' => 'medium'];
        }
        
        // Simple coherence analysis based on topic consistency
        $topicWords = [];
        foreach ($sentences as $sentence) {
            $sentenceWords = preg_split('/\s+/', strtolower($sentence));
            $topicWords = array_merge($topicWords, array_filter($sentenceWords, function($word) {
                return strlen($word) > 4 && !in_array($word, ['this', 'that', 'with', 'from', 'they', 'them', 'have', 'been', 'were']);
            }));
        }
        
        // Calculate word overlap between consecutive sentences
        $overlaps = 0;
        $totalComparisons = 0;
        
        for ($i = 0; $i < count($sentences) - 1; $i++) {
            $words1 = preg_split('/\s+/', strtolower($sentences[$i]));
            $words2 = preg_split('/\s+/', strtolower($sentences[$i + 1]));
            
            $commonWords = array_intersect($words1, $words2);
            $overlaps += count($commonWords);
            $totalComparisons++;
        }
        
        $avgOverlap = $totalComparisons > 0 ? $overlaps / $totalComparisons : 0;
        
        // High coherence might indicate AI generation
        $coherenceScore = min(1.0, $avgOverlap / 5);
        
        return [
            'score' => $coherenceScore,
            'avg_word_overlap' => round($avgOverlap, 2),
            'coherence_level' => $coherenceScore > 0.7 ? 'high' : ($coherenceScore > 0.4 ? 'medium' : 'low'),
        ];
    }

    /**
     * Analyze common AI phrases.
     */
    private function analyzeCommonPhrases(string $text): array
    {
        $aiPhrases = [
            'it is important to note' => 0.8,
            'it should be noted' => 0.7,
            'furthermore' => 0.6,
            'moreover' => 0.6,
            'additionally' => 0.5,
            'in conclusion' => 0.7,
            'in summary' => 0.7,
            'consequently' => 0.6,
            'therefore' => 0.5,
            'thus' => 0.4,
            'hence' => 0.5,
            'as a result' => 0.6,
            'on the other hand' => 0.5,
            'in contrast' => 0.5,
            'similarly' => 0.4,
            'likewise' => 0.4,
        ];
        
        $foundPhrases = [];
        $totalScore = 0;
        
        foreach ($aiPhrases as $phrase => $weight) {
            if (stripos($text, $phrase) !== false) {
                $foundPhrases[] = $phrase;
                $totalScore += $weight;
            }
        }
        
        $phraseScore = min(1.0, $totalScore / 10); // Normalize score
        
        return [
            'score' => $phraseScore,
            'phrases_found' => $foundPhrases,
            'total_phrases' => count($foundPhrases),
        ];
    }

    /**
     * Calculate overall AI confidence score.
     */
    private function calculateAiScore(array $analysis): float
    {
        $indicators = $analysis['indicators'];
        $writingStyle = $analysis['writing_style'];
        
        // Weighted scoring based on different factors
        $complexityScore = ($indicators['complexity']['score'] ?? 0) * 0.25;
        $structureScore = ($indicators['structure']['score'] ?? 0) * 0.20;
        $repetitionScore = ($indicators['repetition']['score'] ?? 0) * 0.20;
        $coherenceScore = ($indicators['coherence']['score'] ?? 0) * 0.15;
        $phraseScore = ($indicators['phrases']['score'] ?? 0) * 0.20;
        
        $totalScore = ($complexityScore + $structureScore + $repetitionScore + $coherenceScore + $phraseScore) * 100;
        
        // Ensure score is within 0-100 range
        return max(0, min(100, round($totalScore, 2)));
    }

    /**
     * Determine confidence level based on analysis quality.
     */
    private function determineConfidenceLevel(array $analysis): string
    {
        $indicators = $analysis['indicators'];
        
        // Count how many indicators have high confidence scores
        $highConfidenceIndicators = 0;
        $totalIndicators = 0;
        
        foreach ($indicators as $indicator) {
            if (isset($indicator['score'])) {
                $totalIndicators++;
                if ($indicator['score'] > 0.7) {
                    $highConfidenceIndicators++;
                }
            }
        }
        
        if ($totalIndicators == 0) {
            return 'low';
        }
        
        $confidenceRatio = $highConfidenceIndicators / $totalIndicators;
        
        if ($confidenceRatio >= 0.7) {
            return 'high';
        } elseif ($confidenceRatio >= 0.4) {
            return 'medium';
        } else {
            return 'low';
        }
    }
}