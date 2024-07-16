<?php

namespace App\Http\Controllers;

use App\Models\CashInItem;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'staff' => User::all(),
            'cash_out' => Department::all()->sum('disbursement'),
            'cash_in' => CashInItem::all()->sum('price'),
            'department' => Department::all(),
            'program' => Program::all(),
        ];
        return view('pages.dashboard', $data);
    }
}
