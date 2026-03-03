<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Local Storage Filesystem Disk for resource files
    |--------------------------------------------------------------------------
    |
    | This driver will be bound as the temporary disk for files enqueued for processing.
    |
    */

    'local_rfiles'  => env('FILESYSTEM_LOCAL', 'local_rfiles'),
    'local_rcovers'  => env('FILESYSTEM_LOCAL_COVERS', 'local_rcovers'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud_rfiles' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Temp Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This driver will be bound as the temporary disk for files enqueued for processing.
    |
    */

    'local_tmp' => env('FILESYSTEM_LOCAL_TMP', 'local_tmp'),

    'cloud_tmp' => env('FILESYSTEM_CLOUD_TMP', 's3_tmp'),

 
    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'local_rfiles' => [
            'driver' => 'local',
            'root' => storage_path('app/public/resource-files'),
            'url' => env('APP_URL').'/storage/resource-files',
            'visibility' => 'public',
        ],

        'local_rcovers' => [
            'driver' => 'local',
            'root' => storage_path('app/public/resource-covers'),
            'url' => env('APP_URL').'/storage/resource-covers',
            'visibility' => 'public',
        ],

        'local_tmp' => [
            'driver' => 'local',
            'root' => storage_path('app/public/resource-tmp'),
            'url' => env('APP_URL').'/storage/resource-tmp',
            'visibility' => 'public',
        ],


        's3_tmp' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_TMP_BUCKET'),
            'url' => env('AWS_TMP_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            // REMOVED: 'visibility' => 'public' - This was causing ACL errors
            // ACLs are blocked by modern S3 buckets. Files remain publicly accessible
            // via bucket policies or explicit GET requests.
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_FILES_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            // REMOVED: 'visibility' => 'public' - This was causing ACL errors
            // ACLs are blocked by modern S3 buckets. Files remain publicly accessible
            // via bucket policies or explicit GET requests.
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];