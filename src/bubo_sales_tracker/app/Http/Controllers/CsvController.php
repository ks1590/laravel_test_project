<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Category;
use App\Models\Item;
use App\Imports\ItemImport;
use App\Imports\CategoryImport;

class CsvController extends Controller
{
    /**
     * Import CSV data to a table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            DB::beginTransaction();

            $file = $request->file('file');

            if (!isset($file)) {
                $result = redirect()->back()->with('danger', 'ファイルが選択されていない、または選択したファイルの形式が正しくありません。CSVファイルを選択してください。');
            } elseif ($file->getClientOriginalExtension() == 'csv') {
                $referer_parameter = $request->headers->get('referer');
                $delete_flag = $request->get('dbReset') == 1 ? true : false;

                if (strpos($referer_parameter, 'item') !== false) {
                    if ($delete_flag) Item::query()->delete();
                    Excel::import(new ItemImport, $file);

                    $result = redirect()->to(route('item.index'))->with('success', '商品をインポートしました。');
//                } elseif (strpos($referer_parameter,'category') !== false) {
//                    Schema::disableForeignKeyConstraints();
//                    if ($delete_flag) Item::query()->delete();
//                    Schema::enableForeignKeyConstraints();
//
//                    Excel::import(new CategoryImport, $file);
//
//                    $result = redirect()->to(route('category.index'))->with('success', 'カテゴリをインポートしました。');
                }
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
