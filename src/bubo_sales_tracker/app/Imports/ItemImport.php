<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Collection;

class ItemImport implements ToCollection,WithHeadingRow,WithCustomCsvSettings
{
    use Importable;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("選択したCSVファイルに商品情報が保存されていません。");
        }

        foreach ($rows as $row) {
            $category_name = Category::where('name', $row['category_name'])->get();

            if ($category_name->isEmpty()) {
                Category::create([
                    'name' => $row['category_name'],
                ]);
            }

            $category_id = Category::where('name', $row['category_name'])->value('id');

            Item::create([
                'category_id' => $category_id,
                'display_name' => $row['display_name'],
                'sku' => $row['sku'],
                'price' => $row['price'],
            ]);
        }
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'Shift-JIS'
        ];
    }
}
