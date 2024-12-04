<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\MonetizationEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampaignController::class, 'index'])->name('home');
Route::get('/monetization/store', [MonetizationController::class, 'store'])->name('storemonetization');
Route::get('/campaigns/revenue', [MonetizationEventController::class, 'showAggregatedRevenue']);
Route::get('/campaigns/revenue/{campaign}', [MonetizationEventController::class, 'showAggregatedRevenueByTime']);
Route::get('/campaigns/revenue/{campaign}/{datetime}', [MonetizationEventController::class, 'showAggregatedRevenueByTimeTerm']);
#Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaign');
#Route::get('/campaigns/{campaign}/publishers', [CampaignController::class, 'publishers'])->name('publishers');