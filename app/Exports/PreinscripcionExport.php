<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PreinscripcionExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : $data->toArray();
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data as $programaData) {
            // Crear una hoja por cada programa
            $sheets[] = new ProgramaSheet($programaData);
        }

        return $sheets;
    }
}

class ProgramaSheet implements FromArray, WithHeadings, WithTitle
{
    protected $programaData;

    public function __construct($programaData)
    {
        $this->programaData = $programaData;
    }

    public function array(): array
    {
        $exportData = [];

        $exportData[] = ["POSTULANTES PREINSCRITOS EN LA " . strtoupper($this->programaData['programa'])];
        $exportData[] = ["Fecha y Hora: " . date('d/m/Y H:m:s')];
        $exportData[] = [""]; // Espacio en blanco para separar

        $exportData[] = ["N°", "N° DOC", "APELLIDOS Y NOMBRES", "CELULAR", "EMAIL"];
        foreach ($this->programaData['data'] as $index => $item) {
            $exportData[] = [
                $index + 1,
                $item['dni'],
                strtoupper($item['paterno']) . ' ' . strtoupper($item['materno']) . ', ' . $item['nombres'],
                $item['celular'],
                strtolower($item['email']),
            ];
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Universidad Nacional del Altiplano',
        ];
    }

    public function title(): string
    {
        return strtoupper($this->programaData['programa']); // Nombre del programa como nombre de la hoja
    }
}
