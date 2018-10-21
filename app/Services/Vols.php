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

class Vols
{
    protected const DEPART = 1;
    protected const COMPAGNIE = 2;
    protected const NUM_VOL = 3;
    protected const SIEGE = 12;

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
    public function programme_vols(UploadedFile $file): StreamedResponse
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

                if('ARR' === $data[0]) {
                    $this->skipRow = false;
                    return;
                }

                if(true === $this->skipRow) {
                    return;
                }

                if(empty($data[self::DEPART])) {
                    return;
                }

                $siege = (int) $data[self::SIEGE];

                $thirdSiege = (int) ($siege * 0.3);

                $this->formatedData[] = [
                    'depart'   => Carbon::createFromFormat('H:i', $data[self::DEPART])->subHours(1)->format('H:i'),
                    'companie' => $data[self::COMPAGNIE],
                    'num_vol'  => $data[self::NUM_VOL],
                    'siege'    => $thirdSiege,
                ];

                $this->formatedData[] = [
                    'depart'   => Carbon::createFromFormat('H:i', $data[self::DEPART])->subHours(2)->format('H:i'),
                    'companie' => $data[self::COMPAGNIE],
                    'num_vol'  => $data[self::NUM_VOL],
                    'siege'    => $siege - 2 * $thirdSiege,
                ];

                $this->formatedData[] = [
                    'depart'   => Carbon::createFromFormat('H:i', $data[self::DEPART])->subHours(3)->format('H:i'),
                    'companie' => $data[self::COMPAGNIE],
                    'num_vol'  => $data[self::NUM_VOL],
                    'siege'    => $thirdSiege,
                ];
            });
        });

        $this->formatedData = collect($this->formatedData)->sortBy(function($item) {
            return Carbon::createFromFormat('H:i', $item['depart']);
        })->values()->toArray();

        foreach ($this->formatedData as $index => $item) {
            $lastDate = 0 === $index ? Carbon::now()->startOfDay() : Carbon::createFromFormat('H:i', $this->formatedData[$index - 1]['depart']);
            $diffInMinutes = Carbon::createFromFormat('H:i', $item['depart'])->diffInMinutes($lastDate);
            $this->formatedData[$index]['temps_attente'] = $diffInMinutes;
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
