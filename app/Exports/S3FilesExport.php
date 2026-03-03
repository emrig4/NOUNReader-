<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Storage;



class S3FilesExport implements FromCollection
{
    public function collection()
    {   
        $dir = request()->dir;
        $files = Storage::disk('s3')->allFiles($dir);
        $filenames = collect($files)->map(function($file){
            return [$file];
        });

        return collect([
            ['filename'],
            $filenames
        ]);
    }
}