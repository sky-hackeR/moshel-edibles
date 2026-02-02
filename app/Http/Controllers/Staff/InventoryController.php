<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Mailables
use App\Mail\Stock\StockInRecorded;
use App\Mail\Stock\LowStockAlert;

// Models
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;

class InventoryController extends Controller
{
    //

    public function inventory() {
        $inventories = Inventory::with(['ingredient.baseUnit'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('staff.inventory', [
            'inventories' => $inventories,
        ]);
    }

}




