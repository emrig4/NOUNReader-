<?php
namespace App\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
/**
 *
 */
class ModelUtil
{

	public static function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();
                }

                return $valid;
            });


        return $models->map(function($name){
            return substr(strrchr($name, "\\"), 1);
            // return $name;
        })->values();
    }

    public static function findModels(){
        $finder = new \Symfony\Component\Finder\Finder;
        $iter = new \hanneskod\classtools\Iterator\ClassIterator($finder->in('app/Models'));
        $models = [];

        // Print the file names of classes, interfaces and traits in 'app/Models'
        foreach ($iter->getClassMap() as $class => $splFileInfo) {
            $classname =  substr(strrchr($class, "\\"), 1);
            // echo $classname.': '.$splFileInfo->getRealPath();
            array_push($models, $classname);

        }

        return $models;

    }
    
}
