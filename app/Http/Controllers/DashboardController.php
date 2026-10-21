<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.index', [
            'activeCount' => Emprestimo::whereNull('returned_at')->count(),
            'recentLoans' => Emprestimo::with('chave')->latest('loaned_at')->limit(5)->get(),
        ]);
    }
}
