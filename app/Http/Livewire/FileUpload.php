<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

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
        $this->validate();
        $this->pdf->store('constancias');
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
