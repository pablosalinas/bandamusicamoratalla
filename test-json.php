<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$fiscalYear = App\Models\FiscalYear::first();
if(!$fiscalYear) die('no');

$pastYears = App\Models\FiscalYear::where('start_date', '<', $fiscalYear->start_date)
    ->orderBy('start_date', 'desc')
    ->take(3)
    ->get()
    ->reverse()
    ->values(); // reset keys to ensure JSON array output

$allYears = $pastYears->push($fiscalYear)->values();

$comparativeData = [
    'labels' => $allYears->pluck('name')->values()->toArray(),
    'income' => $allYears->map->total_income->values()->toArray(),
    'expense' => $allYears->map->total_expense->values()->toArray(),
    'balance' => $allYears->map->balance->values()->toArray(),
];

echo json_encode($comparativeData, JSON_PRETTY_PRINT);
