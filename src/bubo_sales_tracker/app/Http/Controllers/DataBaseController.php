<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataBaseController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $files = Storage::disk('public')->files('backups');
        if (count($files) > 0) {
            $t = (string)date("Y-m-d H:i:s", filectime(storage_path('app/public/' . end($files))));
        } else {
            $t = "-";
        }

        return view('database.index', [
            'time' => $t,
            'filenames' => array_reverse($files)
        ]);
    }

    public function downloadBackup(Request $request)
    {
        try {
            $file = $request->get("filename");

            if (file_exists(storage_path('app/public/' . $file))) {
                return response()->download(storage_path('app/public/' . $file));
            } else {
                return redirect()->back()->with('danger', 'バックアップファイルが存在しません。');
            }
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
