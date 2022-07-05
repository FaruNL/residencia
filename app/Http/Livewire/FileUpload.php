<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Archive;

class FileUpload extends Component
{
    use WithFileUploads;

    public $pdf;

    protected $rules = [
        'pdf' => ['file', 'mimes:pdf', 'max:2048'], // 2MB Max
    ];

    protected $messages = [
        'pdf.mimes' => 'El archivo debe ser de tipo: :values.',
        'pdf.max' => 'El archivo no debe pesar más de :max kilobytes.',
    ];

    public function updatedPdf()
    {
        $this->validate();
    }

    public function savePdf()
    {
        $id = Auth::user()->id;
        $this->validate();
        $this->pdf->store('constancias');
        // $this->pdf->hashName();
        $thesis = Archive::create([
            'title' => 's',
           'url' =>"{$this->pdf->hashName()}",
            'user_id' =>5,
            'course_detail_id'=> 3
        ]);
    }

    public function seePDF($fileName)
    {
        $path = storage_path('app/constancias/'.$fileName);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
