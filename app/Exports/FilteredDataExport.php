namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\TransactionalData; // Ganti dengan model yang sesuai

class FilteredDataExport implements FromCollection, WithHeadings
{
    protected $filteredData;

    public function __construct($filteredData)
    {
        $this->filteredData = $filteredData;
    }

    public function collection()
    {
        return collect($this->filteredData);
    }

    public function headings(): array
    {
        return [
            'Header1', // Ganti dengan nama kolom tabel
            'Header2',
            'Header3',
        ];
    }
}
