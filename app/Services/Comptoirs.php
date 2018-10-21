<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Comptoirs
{
    protected const DEPART = 0;
    protected const COMPAGNIE = 1;
    protected const NUM_VOL = 2;
    protected const COMPTOIR = 9;

    /** @var array  */
    protected $formatedData = [];

    /** @var bool  */
    protected $skipRow = true;

    /** @var Excel */
    protected $excel;

    /** @var string */
    protected $worksheetname;

    /**
     * ConverterController constructor.
     *
     * @param Excel $excel
     */
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * @param UploadedFile $file
     *
     * @return StreamedResponse
     */
    public function affectation_comptoirs(UploadedFile $file): StreamedResponse
    {
        $this->excel->load($file->getRealPath(), function(LaravelExcelReader $reader) {
            $reader
                ->noHeading()
                ->ignoreEmpty()
                ->formatDates(false);

            $this->worksheetname = $reader->getSheetInfoForActive()['worksheetName'] ?? '';

            $reader->each(function(CellCollection $cell) {
                $data = $cell->toArray();

                if(empty($data)) {
                    return;
                }

                if('DEP' === $data[0]) {
                    $this->skipRow = false;
                    return;
                }

                if(true === $this->skipRow) {
                    return;
                }

                if(empty($data[self::DEPART])) {
                    return;
                }

                $this->formatedData[] = [
                    Carbon::createFromFormat('H:i', $data[self::DEPART])->subHours(1)->format('H:i'),
                    $data[self::COMPAGNIE],
                    $data[self::NUM_VOL],
                    $data[self::COMPTOIR],
                ];
            });
        });

        $this->formatedData = collect($this->formatedData)->sortBy(function($item) {
            return Carbon::createFromFormat('H:i', $item[0]);
        })->values()->toArray();

        foreach ($this->formatedData as $index => $item) {
            $comptoirs = explode('/', $item[3]);
            foreach (range(0, 4) as $i) {
                $this->formatedData[$index][$i + 3] = isset($comptoirs[$i]) ? 'Comptoir ' . $comptoirs[$i] : '';
            }
            foreach (range(0, 4) as $i) {
                $this->formatedData[$index][$i + 8] = isset($comptoirs[$i]) ? 'R' . $comptoirs[$i] : '';
            }
            foreach (range(0, 4) as $i) {
                $this->formatedData[$index][$i + 13] = isset($comptoirs[$i]) ? 'Q' . $comptoirs[$i] : '';
            }
        }

        return response()->streamDownload(function() use ($file) {
            echo $this->excel->create($file->getClientOriginalName(), function(LaravelExcelWriter $writer) {
                $writer->sheet($this->worksheetname, function(LaravelExcelWorksheet $worksheet) {
                    $worksheet->fromArray($this->formatedData, null, 'A1', true, false);
                });
            })->string($file->getClientOriginalExtension());
        }, date('d.m.Y H:i:s') . '-' . $file->getClientOriginalName());
    }

}
