<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class CampaignController extends Controller
{
    /**
     * Display list of campaigns and aggregate revenue for each campaign
     */
    public function index()
    {
        // @TODO implement
    }

    /**
     * Display a specific campaign with a hourly breakdown of all revenue
     */
    public function show(Campaign $campaign)
    {
        // @TODO implement
    }

    /**
     * Display a specific campaign with the aggregate revenue by utm_term
     */
    public function publishers(Campaign $campaign)
    {
        // @TODO implement
    }
}
