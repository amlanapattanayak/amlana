<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonetizationEvent extends Model
{
    use HasFactory;
    // Define the table name (optional, Laravel will assume 'products' by default)
    protected $table = 'monetization_events';

    // Define which attributes are mass assignable (this helps prevent mass assignment vulnerabilities)
    protected $fillable = ['campaign_id', 'term_id', 'monetization_timestamp', 'revenue'];

    // Optional: If you don't want Laravel to automatically manage timestamps
    public $timestamps = false; // Set to false if you don't have `created_at` and `updated_at` columns

    // Relationship to Campaign
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Relationship to Term
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
