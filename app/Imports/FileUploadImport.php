<?php

namespace App\Imports;

use App\Models\PhotonParameter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;


class PhotonParameterImport implements ToModel, WithStartRow, WithEvents
{
    public $sheetName;


    
    public function __construct(){
        $this->sheetName = '';
    }


    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    *    
    */
    public function model(array $row)
    {
      try {
        
        $element = $this->sheetName;
        if($this->sheetName == 'Sheet81'){
            $element = 'TI';
        }
        
        return  PhotonParameter::create(
          [
            'element' => $element,
            'energy_level' => $row[0],
            'coherent_scattering' => $row[1],
            'incoherent_scattering' => $row[2],
            'photoelectric_abs' => $row[3],
            'nuclear_field_par' => $row[4],
            'electron_field_par' => $row[5],
            'total_with_coh' => $row[6],
            'total_without_coh' => $row[7],
          ]
        );

      } catch (\Throwable $th) {
        throw $th;
      }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetName = $event->getSheet()->getDelegate()->getTitle();
            }
        ];
    }
}
