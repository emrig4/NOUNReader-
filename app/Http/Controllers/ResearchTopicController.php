<?php

namespace App\Http\Controllers;

use App\Models\ResearchTopic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ResearchTopicController extends Controller
{
    /**
     * Display the topics suggestion interface
     */
    public function index()
    {
        $departments = ResearchTopic::$departments;
        $types = ResearchTopic::$types;
        $recentTopics = ResearchTopic::published()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('topics-suggestion.index', compact('departments', 'types', 'recentTopics'));
    }

    /**
     * Get topic suggestions based on department and type
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'department' => 'required|string',
            'type' => 'required|in:project,thesis,dissertation'
        ]);

        $department = $request->input('department');
        $type = $request->input('type');

        // Get existing topics from database
        $existingTopics = ResearchTopic::getSuggestions($department, $type, 5);

        // Generate additional random suggestions
        $generatedTopics = ResearchTopic::generateRandomTopics($department, $type, 10);

        // Combine and format suggestions
        $suggestions = [];

        // Add existing topics
        foreach ($existingTopics as $topic) {
            $suggestions[] = [
                'title' => $topic->title,
                'description' => $topic->description,
                'type' => $topic->type,
                'department' => $topic->department,
                'source' => 'database'
            ];
        }

        // Add generated topics
        foreach ($generatedTopics as $topicTitle) {
            $suggestions[] = [
                'title' => $topicTitle,
                'description' => $this->generateDescription($topicTitle, $department, $type),
                'type' => $type,
                'department' => $department,
                'source' => 'generated'
            ];
        }

        // Shuffle suggestions and limit to 15
        shuffle($suggestions);
        $suggestions = array_slice($suggestions, 0, 15);

        return response()->json([
            'success' => true,
            'data' => [
                'suggestions' => $suggestions,
                'department' => $department,
                'type' => $type,
                'total_found' => count($suggestions)
            ]
        ]);
    }

    /**
     * Search topics by keywords
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'keywords' => 'required|string|min:2',
            'department' => 'nullable|string',
            'type' => 'nullable|in:project,thesis,dissertation'
        ]);

        $keywords = $request->input('keywords');
        $department = $request->input('department');
        $type = $request->input('type');

        $query = ResearchTopic::published()->search($keywords);

        if ($department) {
            $query->byDepartment($department);
        }

        if ($type) {
            $query->byType($type);
        }

        $results = $query->limit(20)->get();

        $suggestions = $results->map(function ($topic) {
            return [
                'title' => $topic->title,
                'description' => $topic->description,
                'type' => $topic->type,
                'department' => $topic->department,
                'source' => 'database'
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'suggestions' => $suggestions,
                'keywords' => $keywords,
                'total_found' => count($suggestions)
            ]
        ]);
    }

    /**
     * Get popular topics
     */
    public function getPopular(): JsonResponse
    {
        $popularTopics = ResearchTopic::published()
            ->selectRaw('title, department, type, COUNT(*) as view_count')
            ->groupBy('title', 'department', 'type')
            ->orderBy('view_count', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'popular_topics' => $popularTopics
            ]
        ]);
    }

    /**
     * Get topics by department
     */
    public function getByDepartment(Request $request): JsonResponse
    {
        $request->validate([
            'department' => 'required|string'
        ]);

        $department = $request->input('department');

        $topics = ResearchTopic::published()
            ->byDepartment($department)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $suggestions = $topics->map(function ($topic) {
            return [
                'title' => $topic->title,
                'description' => $topic->description,
                'type' => $topic->type,
                'department' => $topic->department,
                'source' => 'database'
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'suggestions' => $suggestions,
                'department' => $department,
                'total_found' => count($suggestions)
            ]
        ]);
    }

    /**
     * Get all departments
     */
    public function getDepartments(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'departments' => ResearchTopic::$departments,
                'types' => ResearchTopic::$types
            ]
        ]);
    }

    /**
     * Save a topic to favorites (if user is authenticated)
     */
    public function saveFavorite(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'department' => 'required|string',
            'type' => 'required|in:project,thesis,dissertation'
        ]);

        // In a real application, you would save this to user's favorites
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Topic saved to favorites successfully!'
        ]);
    }

    /**
     * Get topic statistics
     */
    public function getStatistics(): JsonResponse
    {
        $statistics = ResearchTopic::getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Generate description for a topic
     */
    private function generateDescription($title, $department, $type)
    {
        $descriptions = [
            'project' => 'This project focuses on practical implementation and real-world application of theoretical concepts in ' . $department . '.',
            'thesis' => 'This thesis provides a comprehensive analysis and critical evaluation of key aspects in ' . $department . '.',
            'dissertation' => 'This dissertation offers in-depth research and scholarly analysis of advanced topics in ' . $department . '.'
        ];

        return $descriptions[$type] ?? 'This research work explores important aspects of ' . $department . '.';
    }

    /**
     * Export topics to CSV
     */
    public function exportTopics(Request $request): JsonResponse
    {
        $department = $request->input('department');
        $type = $request->input('type');

        $query = ResearchTopic::published();

        if ($department) {
            $query->byDepartment($department);
        }

        if ($type) {
            $query->byType($type);
        }

        $topics = $query->get();

        // Generate CSV content
        $csvContent = "Title,Description,Department,Type,Difficulty Level\n";
        foreach ($topics as $topic) {
            $csvContent .= '"' . str_replace('"', '""', $topic->title) . '",';
            $csvContent .= '"' . str_replace('"', '""', $topic->description) . '",';
            $csvContent .= '"' . $topic->department . '",';
            $csvContent .= '"' . $topic->type . '",';
            $csvContent .= '"' . $topic->difficulty_level . '"' . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="research_topics.csv"');
    }

 /**
 * Show the topics suggestion form
 */
public function showForm()
{
    // Static array of departments (no model dependency needed)
    $departments = [
        'Computer Science',
        'Information Technology', 
        'Software Engineering',
        'Data Science',
        'Artificial Intelligence',
        'Cybersecurity',
        'Network Engineering',
        'Web Development',
        'Mobile Application Development',
        'Database Management',
        'Business Administration',
        'Marketing',
        'Finance',
        'Accounting',
        'Human Resource Management',
        'Operations Management',
        'Project Management',
        'Economics',
        'Banking and Finance',
        'International Business',
        'Electrical Engineering',
        'Mechanical Engineering',
        'Civil Engineering',
        'Chemical Engineering',
        'Industrial Engineering',
        'Environmental Engineering',
        'Biomedical Engineering',
        'Aerospace Engineering',
        'Petroleum Engineering',
        'Materials Science',
        'Medicine',
        'Nursing',
        'Pharmacy',
        'Public Health',
        'Dentistry',
        'Medical Laboratory Science',
        'Psychology',
        'Sociology',
        'Political Science',
        'International Relations',
        'Criminology',
        'Social Work',
        'Education',
        'Special Education',
        'Curriculum Development',
        'Educational Technology',
        'Linguistics',
        'English Literature',
        'Creative Writing',
        'Philosophy',
        'History',
        'Archaeology',
        'Anthropology',
        'Law',
        'Journalism',
        'Mass Communication',
        'Fine Arts',
        'Music',
        'Theater Arts',
        'Photography',
        'Graphic Design',
        'Architecture',
        'Urban Planning',
        'Landscape Architecture',
        'Mathematics',
        'Statistics',
        'Physics',
        'Chemistry',
        'Biology',
        'Environmental Science',
        'Geology',
        'Oceanography',
        'Meteorology',
        'Astronomy',
        'Agriculture',
        'Food Science',
        'Veterinary Medicine',
        'Forestry',
        'Horticulture',
        'Animal Science',
        'Sports Science',
        'Physical Education',
        'Recreation Management',
        'Tourism Management',
        'Hospitality Management',
        'Library Science',
        'Museum Studies',
        'Information Science'
    ];
    
    // Available work types
    $types = ['project', 'thesis', 'dissertation'];
    
    // Pass data to the view
    return view('topics-suggestion.index', compact('departments', 'types'));
}
}