<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Services\BulkOperations\BulkRegistry;
use Maatwebsite\Excel\Facades\Excel;

use App\Services\UnitConversion\UnitConverter;
use App\Models\SiteInfo as Setting;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ImportExportLog;


use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;


class BulkController extends Controller
{
    //
    public function bulkOperations(){
        $statistics = [
            'imports' => ImportExportLog::where('operation', 'import')->count(),
            'exports' => ImportExportLog::where('operation', 'export')->count(),
            'success' => ImportExportLog::where('status', 'success')->count(),
            'failed'  => ImportExportLog::where('status', 'failed')->count(),
        ];

        $history = ImportExportLog::with('admin')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.bulkOperations', [
            'statistics'=> $statistics,
            'history'=> $history,        
        ]);
    }

    public function downloadTemplate($module){
        if (!BulkRegistry::exists($module)) {

            abort(404);

        }

        $export = BulkRegistry::export($module);

        return Excel::download(

            new $export,

            BulkRegistry::template($module)

        );
    }

    public function unitManager(){
        return view('admin.unitManager');
    }

    public function systemSettings(){
        return view('admin.systemSettings');
    }

    
}
