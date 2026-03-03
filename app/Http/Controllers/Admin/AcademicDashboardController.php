<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AcademicDashboardController extends Controller
{
    /**
     * Check user session validity
     * Fast endpoint for loader validation
     */
    public function checkSession(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Not authenticated'
                ], 401);
            }

            // Check if session is still valid
            $sessionValid = Cache::has('user_' . $user->id . '_session');
            
            return response()->json([
                'authenticated' => true,
                'user_id' => $user->id,
                'user_name' => $user->name ?? 'User',
                'session_valid' => $sessionValid,
                'timestamp' => now()->timestamp
            ]);

        } catch (\Exception $e) {
            Log::error('Session check failed: ' . $e->getMessage());
            
            return response()->json([
                'authenticated' => false,
                'error' => 'Session check failed'
            ], 500);
        }
    }

    /**
     * Load dashboard statistics (optimized for your theme)
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Cache frequently accessed data for 5 minutes
            $stats = Cache::remember('dashboard_stats_' . $user->id, 300, function() use ($user) {
                return [
                    'total_projects' => $this->getUserProjectCount($user),
                    'total_downloads' => $this->getUserDownloadCount($user),
                    'unique_reads' => $this->getUserReadCount($user),
                    'favorite_projects' => $this->getUserFavoritesCount($user),
                    'recent_activity' => $this->getRecentActivity($user),
                    'storage_used' => $this->getStorageUsed($user),
                    'last_updated' => now()->toISOString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats,
                'theme_colors' => [
                    'primary' => '#2DACE3',
                    'secondary' => '#25D366',
                    'background' => '#ffffff'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard stats loading failed: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to load dashboard data',
                'message' => 'Please check your connection and try again'
            ], 500);
        }
    }

    /**
     * Get user projects count (optimized query)
     */
    private function getUserProjectCount($user): int
    {
        // Adjust this based on your actual project model
        // Example using a hypothetical Project model
        // return \App\Models\Project::where('user_id', $user->id)->count();
        
        // For now, return mock data
        return rand(25, 150);
    }

    /**
     * Get user downloads count
     */
    private function getUserDownloadCount($user): int
    {
        // Adjust based on your Download model
        // return \App\Models\Download::where('user_id', $user->id)->count();
        
        return rand(50, 300);
    }

    /**
     * Get unique reads count
     */
    private function getUserReadCount($user): int
    {
        // Return unique project reads
        return rand(100, 500);
    }

    /**
     * Get favorite projects count
     */
    private function getUserFavoritesCount($user): int
    {
        // Return user's favorite projects
        return rand(10, 50);
    }

    /**
     * Get recent activity (last 5 activities)
     */
    private function getRecentActivity($user): array
    {
        // Return recent user activities
        return [
            [
                'type' => 'download',
                'title' => 'Downloaded: Research Methodology Guide',
                'timestamp' => now()->subMinutes(15)->toISOString(),
                'icon' => 'download'
            ],
            [
                'type' => 'view',
                'title' => 'Viewed: Computer Science Thesis',
                'timestamp' => now()->subHours(2)->toISOString(),
                'icon' => 'eye'
            ],
            [
                'type' => 'favorite',
                'title' => 'Added to favorites: Data Mining Project',
                'timestamp' => now()->subDays(1)->toISOString(),
                'icon' => 'heart'
            ]
        ];
    }

    /**
     * Get storage used by user
     */
    private function getStorageUsed($user): array
    {
        // Return storage usage in MB
        $usedMB = rand(50, 500);
        $limitMB = 1000;
        
        return [
            'used' => $usedMB,
            'limit' => $limitMB,
            'percentage' => round(($usedMB / $limitMB) * 100, 1)
        ];
    }

    /**
     * Validate current session
     */
    public function validateSession(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Session expired'
                ], 401);
            }

            // Update session timestamp
            Cache::put('user_' . $user->id . '_session', true, 3600); // 1 hour

            return response()->json([
                'valid' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'expires_at' => now()->addHour()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Session validation failed: ' . $e->getMessage());
            
            return response()->json([
                'valid' => false,
                'error' => 'Validation failed'
            ], 500);
        }
    }

    /**
     * Get user profile data (lightweight)
     */
    public function getUserProfile(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $profile = Cache::remember('user_profile_' . $user->id, 600, function() use ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ?? null,
                    'role' => $user->role ?? 'user',
                    'member_since' => $user->created_at->format('Y-m-d'),
                    'preferences' => [
                        'theme' => 'light',
                        'notifications' => true,
                        'language' => 'en'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $profile
            ]);

        } catch (\Exception $e) {
            Log::error('Profile loading failed: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to load profile',
                'message' => 'Please try again later'
            ], 500);
        }
    }

    /**
     * Health check endpoint for loader system
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'theme' => 'academic-repository',
            'loader_ready' => true
        ]);
    }
}