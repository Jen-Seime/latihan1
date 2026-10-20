<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // Query Product biasa otomatis mengecualikan soft-deleted records.
        $activeProducts = Product::count();
        $totalStock = Product::sum('stock');
        $categoryCount = Category::count();
        $trashCount = $request->user()->isAdmin()
            ? Product::onlyTrashed()->count()
            : null;
        $latestProducts = Product::with('category')->orderByDesc('id')->take(5)->get();

        return view('dashboard', compact(
            'activeProducts', 'totalStock', 'categoryCount', 'trashCount', 'latestProducts'
        ));
    }
}
