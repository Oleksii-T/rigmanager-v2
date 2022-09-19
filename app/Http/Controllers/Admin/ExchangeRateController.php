<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use App\Http\Requests\Admin\ExchangeRateRequest;
use Illuminate\Support\Facades\Artisan;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.exchangeRates.index');
        }

        $exchangeRates = ExchangeRate::query();

        if ($request->role !== null) {
            $exchangeRates->whereHas('roles', function($q) use ($request){
                $q->where('roles.id', $request->role);
            });
        }

        return ExchangeRate::dataTable($exchangeRates);
    }

    // sync rates with currencies
    public function sync()
    {
        Artisan::call('db:seed --class=ExchangeRateSeeder');

        return $this->jsonSuccess('ExchangeRate synced successfully');
    }

    public function create()
    {
        return view('admin.exchangeRates.create');
    }

    public function edit(ExchangeRate $exchangeRate)
    {
        return view('admin.exchangeRates.edit', compact('exchangeRate'));
    }

    public function update(ExchangeRateRequest $request, ExchangeRate $exchangeRate)
    {
        $input = $request->validated();
        $input['auto_update'] = $input['auto_update']??false;

        $exchangeRate->update($input);

        return $this->jsonSuccess('ExchangeRate updated successfully');
    }
}
