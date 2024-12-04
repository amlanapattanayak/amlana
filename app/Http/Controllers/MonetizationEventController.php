<?php

namespace App\Http\Controllers;

use App\Models\MonetizationEvent;
use App\Models\Campaign;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonetizationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //echo '<pre>',print_r($request),'</pre>';

        // Validate input data
        $request->validate([
            'utm_campaign' => 'required|string|max:255',
            'utm_term' => 'required|string|max:255',
            'monetization_timestamp' => 'required|date',
            'revenue' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Ensure unique UTM Campaign
            $Campaign = Campaign::firstOrCreate(
                ['utm_campaign' => $request->utm_campaign]
            );
            //dd($request->utm_term);
            // Ensure unique UTM Term
            $Term = Term::firstOrCreate(
                ['utm_term' => $request->utm_term]
            );

            // Create Monetization Event
            $monetizationEvent = MonetizationEvent::create([
                'campaign_id' => $Campaign->id,
                'term_id' => $Term->id,
                'monetization_timestamp' => $request->monetization_timestamp,
                'revenue' => $request->revenue,
            ]);

            DB::commit();

            // Return the ID of the new monetization event
            return response()->json([
                'message' => 'Monetization event stored successfully.',
                'monetization_event_id' => $monetizationEvent->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error storing monetization event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Display the aggregated campaign broken down by date and hour.
     */
    public function showAggregatedRevenue()
    {
        // Aggregate revenue by both campaign_id and term_id
        $aggCampRev = MonetizationEvent::select('campaign_id', 'term_id', DB::raw('SUM(revenue) as total_revenue'))
            ->groupBy('campaign_id')
            ->take(10)
            ->get();

        // Optionally, you can eager load the related campaign and term names
        foreach ($aggCampRev as $campRev) {
            $campRev->utm_campaign = $campRev->campaign->utm_campaign;  // Assuming campaign has a 'utm_campaign' field
            $campRev->revenue = $campRev->total_revenue; 
            $campRev->revenue = $campRev->total_revenue; 
            $campRev->cur_url = url('campaigns/revenue/'.$campRev->campaign_id);
        }

        return view('monetizationEvents.revenuecampaign', compact('aggCampRev'));
    }

    
    /**
     * Display the show Aggregated Revenue By Time broken down by date and hour.
     */
    public function showAggregatedRevenueByTime($campaign_id)
    {
        // Aggregate revenue by both campaign_id and term_id
        $aggCampTermRev = MonetizationEvent::select('campaign_id', 'term_id', DB::raw('SUM(revenue) as total_revenue'), DB::raw('DATE(monetization_timestamp) as date'), DB::raw('HOUR(monetization_timestamp) as hour'))
            ->where('campaign_id', $campaign_id)
            ->groupBy(DB::raw('DATE(monetization_timestamp)'), DB::raw('HOUR(monetization_timestamp)'))
            ->orderBy('date', 'desc')
            ->orderBy('hour', 'asc')
            ->get();

        // Optionally, you can eager load the related campaign and term names
        foreach ($aggCampTermRev as $campTermRev) {
            $campTermRev->utm_campaign = $campTermRev->campaign->utm_campaign;  // Assuming campaign has a 'utm_campaign' field
            $campTermRev->utm_term = $campTermRev->term->utm_term;  // Assuming campaign has a 'utm_campaign' field
            $campTermRev->revenue = $campTermRev->total_revenue; 
            $campTermRev->cur_url = url('campaigns/revenue/'.$campTermRev->campaign_id.'/'.$campTermRev->date);
        }
        //dd($aggCampTermRev);
        return view('monetizationEvents.revenuecampaigndate', compact('aggCampTermRev'));
    }

    
    /**
     * Display the show Aggregated Revenue By Time Term broken down by date and hour.
     */
    public function showAggregatedRevenueByTimeTerm($campaign_id, $date)
    {
         // Aggregate revenue by both campaign_id and term_id
         $aggCampTimeTermRev = MonetizationEvent::select('campaign_id', 'term_id', DB::raw('SUM(revenue) as total_revenue'), DB::raw('DATE(monetization_timestamp) as date'), DB::raw('HOUR(monetization_timestamp) as hour'))
         ->where('campaign_id', $campaign_id)
         ->where(DB::raw('DATE(monetization_timestamp)'), $date)
         ->groupBy('term_id', DB::raw('HOUR(monetization_timestamp)'))
         ->orderBy('date', 'desc')
         ->orderBy('hour', 'asc')
         ->get();

        // Optionally, you can eager load the related campaign and term names
        foreach ($aggCampTimeTermRev as $campTermRev) {
            $campTermRev->utm_campaign = $campTermRev->campaign->utm_campaign;  // Assuming campaign has a 'utm_campaign' field
            $campTermRev->utm_term = $campTermRev->term->utm_term;  // Assuming campaign has a 'utm_campaign' field
            $campTermRev->revenue = $campTermRev->total_revenue; 
            $campTermRev->cur_url = url('campaigns/revenue/'.$campTermRev->campaign_id.'/'.$campTermRev->date);
        }
        //dd($aggCampTimeTermRev);
        return view('monetizationEvents.revenueterm', compact('aggCampTimeTermRev'));
    }

    /**
     * Display the specified resource.
     */
    public function show(monetization_event $monetization_event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(monetization_event $monetization_event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, monetization_event $monetization_event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(monetization_event $monetization_event)
    {
        //
    }
}
