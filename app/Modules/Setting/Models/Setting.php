<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Modules\Setting\Events\SettingSaved;

class Setting extends Model
{

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'plainValue'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'isTranslatable' => 'boolean',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => SettingSaved::class,
    ];

    

    /**
     * Get all settings with cache support.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCached()
    {
        return Cache::tags(['settings'])->rememberForever('settings.all:' . locale(), function () {
            return self::all()->mapWithKeys(function ($setting) {
                return [$setting->name => $setting->value];
            });
        });
    }

    /**
     * Determine if the given setting name exists.
     *
     * @param string $name
     * @return bool
     */
    public static function has($name)
    {
        return static::where('name', $name)->exists();
    }

    /**
     * Get setting for the given name.
     *
     * @param string $name
     * @param mixed $default
     * @return string|array
     */
    public static function get($name, $default = null)
    {
        return static::where('name', $name)->first()->value ?? $default;
    }

    /**
     * Set the given setting.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set($name, $value)
    {

        static::updateOrCreate(['name' => $name], ['plainValue' => $value]);
    }

    /**
     * Set the given settings.
     *
     * @param array $settings
     * @return void
     */
    public static function setMany($settings)
    {
        foreach ($settings as $name => $value) {
            self::set($name, $value);
        }
    }


    /**
     * Get the value of the setting.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if ($this->isTranslatable) {
            return $this->translateOrDefault(locale())->value ?? null;
        }

        return unserialize($this->plainValue);
    }

    /**
     * Set the value of the setting.
     *
     * @param mixed $value
     * @return mixed
     */
    public function setPlainValueAttribute($value)
    {
        $this->attributes['plainValue'] = serialize($value);
    }
}
