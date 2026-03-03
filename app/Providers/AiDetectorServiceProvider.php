<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class AiDetectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/ai-detector.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'ai-detector');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        
        // Publish config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/ai-detector.php', 'ai-detector'
        );
        
        // Register custom Blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for AI detection.
     */
    private function registerBladeDirectives(): void
    {
        // AI Score badge directive
        Blade::directive('aiScoreBadge', function ($expression) {
            return "<?php 
                \$score = $expression;
                \$class = \$score >= 70 ? 'ai-likely' : (\$score >= 40 ? 'ai-uncertain' : 'human-likely');
                \$label = \$score >= 70 ? 'AI Likely' : (\$score >= 40 ? 'Uncertain' : 'Human Likely');
                echo '<span class=\"ai-score-badge ' . \$class . '\">' . \$label . ' (' . \$score . '%)</span>';
            ?>";
        });

        // AI Confidence level directive
        Blade::directive('aiConfidenceLevel', function ($expression) {
            return "<?php 
                \$confidence = $expression;
                \$class = \$confidence === 'high' ? 'confidence-high' : (\$confidence === 'medium' ? 'confidence-medium' : 'confidence-low');
                \$label = ucfirst(\$confidence);
                echo '<span class=\"confidence-level ' . \$class . '\">' . \$label . '</span>';
            ?>";
        });
    }
}