<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (!empty($this->search)) {
            return Book::searchBooksAdvanced($this->search);
        } else {
            return Book::with(['authors', 'publisher'])->orderBy('name')->get();
        }
    }

    public function headings(): array
    {
        return [
            'Name',
            'Authors',
            'Publisher',
            'Price',
            'ISBN',
            'Cover Image URL',
            'Bibliography',
        ];
    }

    public function map($book): array
    {
        return [
            $book->name,
            $book->authors->pluck('name')->join(', ') ?: 'No Authors',
            $book->publisher->name ?? 'No Publisher',
            '$' . number_format($book->formatted_price, 2),
            "'" . $book->isbn,
            $book->cover_image,
            $book->bibliography ?: 'No Bibliography',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Name
            'B' => 25, // Authors
            'C' => 20, // Publisher
            'D' => 15, // Price
            'E' => 20, // ISBN
            'F' => 50, // Cover Image URL
            'G' => 30, // Bibliography
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
