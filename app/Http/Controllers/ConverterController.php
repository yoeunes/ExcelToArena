<?php

namespace App\Http\Controllers;

use App\Services\Comptoirs;
use App\Services\Vols;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConverterController extends Controller
{
    protected const AFFECTATION_COMPTOIRS = 'affectation_comptoirs';
    protected const PROGRAMME_VOLS = 'programme_vols';

    /**
     * @return StreamedResponse
     */
    public function store(): StreamedResponse
    {
        request()->validate([
            'fichier_excel' => 'required|file',
            'type'          => 'required|in:'.self::AFFECTATION_COMPTOIRS.','.self::PROGRAMME_VOLS,
        ]);

        if (self::PROGRAMME_VOLS === request('type')) {
            return $this->programme_vols(request('fichier_excel'));
        }

        return $this->affectation_comptoirs(request('fichier_excel'));
    }

    /**
     * @param UploadedFile $file
     *
     * @return StreamedResponse
     */
    public function programme_vols(UploadedFile $file): StreamedResponse
    {
        return app(Vols::class)->programme_vols($file);
    }

    /**
     * @param UploadedFile $file
     *
     * @return StreamedResponse
     */
    public function affectation_comptoirs(UploadedFile $file): StreamedResponse
    {
        return app(Comptoirs::class)->affectation_comptoirs($file);
    }
}
