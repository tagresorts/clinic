<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['item'])->latest()->paginate(25);
        return view('stock_movements.index', compact('movements'));
    }
}
