<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchTopic extends Model
{
    protected $table = 'research_topics';

    protected $fillable = [
        'title',
        'description',
        'department',
        'category',
        'type',
        'difficulty_level',
        'is_published',
        'tags',
        'keywords'
    ];

    protected $casts = [
        'tags' => 'array',
        'keywords' => 'array',
        'is_published' => 'boolean',
    ];

    // Department categories
    public static $departments = [
        'Mechanical Engineering',
        'Microbiology',
        'Electrical Engineering',
        'Agricultural Science',
        'Food Technology',
        'Accountancy',
        'Fisheries',
        'Biochemistry',
        'Economics',
        'Agricultural Engineering',
        'Computer Science',
        'Civil Engineering',
        'Radiography',
        'Business Administration',
        'Mass Communication',
        'Botany',
        'Food Science',
        'Law',
        'Agric Economics',
        'Sociology',
        'Zoology',
        'Physics',
        'Mathematics',
        'Electrical Electronics Engineering',
        'Chemical Engineering',
        'Political Science',
        'Marketing',
        'Psychology',
        'Radiology',
        'Medicine and Surgery',
        'Public Health',
        'Pharmacy',
        'Nursing and Midwifery',
        'Architecture',
        'Estate Management',
        'Literature in English'
    ];

    // Topic types
    public static $types = [
        'project',
        'thesis',
        'dissertation'
    ];

    // Difficulty levels
    public static $difficulty_levels = [
        'beginner',
        'intermediate',
        'advanced'
    ];

    /**
     * Scope for filtering by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope for filtering by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for published topics only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Search topics by keywords
     */
    public function scopeSearch($query, $keywords)
    {
        return $query->where(function($q) use ($keywords) {
            $q->where('title', 'LIKE', "%{$keywords}%")
              ->orWhere('description', 'LIKE', "%{$keywords}%")
              ->orWhere('tags', 'LIKE', "%{$keywords}%");
        });
    }

    /**
     * Get suggested topics based on department and type
     */
    public static function getSuggestions($department, $type, $limit = 10)
    {
        return self::published()
            ->byDepartment($department)
            ->byType($type)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Generate random topic suggestions
     */
    public static function generateRandomTopics($department, $type, $count = 5)
    {
        $baseTopics = self::getBaseTopics($department);
        $suggestions = [];

        for ($i = 0; $i < $count; $i++) {
            $baseTopic = $baseTopics[array_rand($baseTopics)];
            $variations = self::generateVariations($baseTopic, $type);
            $suggestions[] = $variations[array_rand($variations)];
        }

        return $suggestions;
    }

    /**
     * Base topics by department
     */
    private static function getBaseTopics($department)
    {
        $topics = [
            'Computer Science' => [
                'Machine Learning Applications in Healthcare',
                'Blockchain Technology for Financial Security',
                'Internet of Things (IoT) Smart Home Systems',
                'Artificial Intelligence in Education',
                'Cybersecurity Framework Development',
                'Mobile App Development Best Practices',
                'Cloud Computing Security Challenges',
                'Data Mining for Business Intelligence',
                'Web Development Technologies Comparison',
                'Network Performance Optimization'
            ],
            'Mechanical Engineering' => [
                'Automotive Engine Performance Analysis',
                'Renewable Energy Systems Design',
                'Manufacturing Process Optimization',
                'Thermal Management in Electronics',
                'Robotics and Automation Systems',
                'Material Science in Engineering',
                'HVAC System Design and Efficiency',
                'Fluid Mechanics Applications',
                'Structural Analysis of Bridges',
                'Energy Conservation Strategies'
            ],
            'Electrical Engineering' => [
                'Power System Stability Analysis',
                'Renewable Energy Integration',
                'Control Systems Design',
                'Signal Processing Applications',
                'Motor Drive Technology',
                'Electrical Safety Standards',
                'Power Quality Improvement',
                'Smart Grid Technology',
                'Electromagnetic Compatibility',
                'Power Electronics Converters'
            ],
            'Business Administration' => [
                'Digital Marketing Strategy Analysis',
                'Customer Relationship Management',
                'Supply Chain Optimization',
                'Employee Performance Management',
                'Financial Risk Assessment',
                'Organizational Change Management',
                'E-commerce Business Models',
                'Strategic Planning in SMEs',
                'Leadership Styles in Modern Organizations',
                'Corporate Social Responsibility Impact'
            ],
            'Medicine and Surgery' => [
                'Telemedicine Adoption in Rural Areas',
                'Preventive Healthcare Strategies',
                'Medical Device Innovation',
                'Healthcare Quality Improvement',
                'Electronic Health Records Systems',
                'Public Health Policy Analysis',
                'Clinical Decision Support Systems',
                'Healthcare Cost Management',
                'Patient Safety Protocols',
                'Medical Research Ethics'
            ],
            'Civil Engineering' => [
                'Sustainable Building Materials',
                'Urban Planning and Development',
                'Transportation System Design',
                'Environmental Impact Assessment',
                'Bridge Design and Construction',
                'Geotechnical Engineering Applications',
                'Water Resource Management',
                'Construction Project Management',
                'Building Information Modeling (BIM)',
                'Earthquake Resistant Design'
            ]
        ];

        return $topics[$department] ?? [
            'Research Methodology in Modern Applications',
            'Technology Integration in Professional Practice',
            'Sustainable Development Practices',
            'Innovation and Problem Solving',
            'Quality Management Systems',
            'Professional Ethics and Standards',
            'Data Analysis and Interpretation',
            'Industry Best Practices Study',
            'Market Analysis and Trends',
            'Future Perspectives in the Field'
        ];
    }

    /**
     * Generate topic variations based on type
     */
    private static function generateVariations($baseTopic, $type)
    {
        $variations = [
            'project' => [
                $baseTopic . ': A Practical Implementation Study',
                $baseTopic . ': Design and Development Approach',
                $baseTopic . ': Case Study Analysis',
                $baseTopic . ': Implementation and Evaluation',
                $baseTopic . ': Comparative Study'
            ],
            'thesis' => [
                'The Impact of ' . $baseTopic . ' on Industry Performance',
                'Analyzing ' . $baseTopic . ': A Comprehensive Study',
                $baseTopic . ': Challenges and Opportunities',
                'The Role of ' . $baseTopic . ' in Modern Applications',
                $baseTopic . ': A Critical Analysis'
            ],
            'dissertation' => [
                'Advanced Studies in ' . $baseTopic . ': Theory and Practice',
                $baseTopic . ': A Multi-dimensional Analysis',
                'The Future of ' . $baseTopic . ': Trends and Implications',
                $baseTopic . ': Innovation and Transformation',
                $baseTopic . ': Strategic Perspectives and Applications'
            ]
        ];

        return $variations[$type] ?? $variations['project'];
    }

    /**
     * Get topic statistics
     */
    public static function getStatistics()
    {
        return [
            'total_topics' => self::published()->count(),
            'by_department' => self::published()
                ->selectRaw('department, COUNT(*) as count')
                ->groupBy('department')
                ->orderBy('count', 'desc')
                ->get(),
            'by_type' => self::published()
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'recent_topics' => self::published()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }
}