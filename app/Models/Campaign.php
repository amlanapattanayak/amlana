<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    // Define the table name (optional, Laravel will assume 'products' by default)
    //protected $table = 'campaigns';

    // Define which attributes are mass assignable (this helps prevent mass assignment vulnerabilities)
    protected $fillable = ['utm_campaign'];

    // Optional: If you don't want Laravel to automatically manage timestamps
    public $timestamps = false; // Set to false if you don't have `created_at` and `updated_at` columns

    // Relationship to MonetizationEvent
    public function monetizationEvents()
    {
        return $this->hasMany(MonetizationEvent::class);
    }
}
