<?php

namespace App\Console\Commands;
ini_set('memory_limit', '1G');

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Http\Controllers\MonetizationEventController;


class ImportStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-stats {filename : The csv file path to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stats from CSV files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the CSV filename path from the argument
        $filePath = $this->argument('filename');
        //$this->info($filePath);

        // Check if the file exists
        if (!file_exists($filePath)) {
            $this->error("The file does not exist.");
            return 1;
        }

        // Attempt to read the CSV file
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0); // Assuming the first row contains headers
            $records = $csv->getRecords(); // Get the records from the CSV file
        } catch (\Exception $e) {
            $this->error("Failed to read the CSV file: " . $e->getMessage());
            return 1;
        }
        $count = 0;
        // Loop through each record and insert it into the database
        foreach ($records as $record) {
            //echo '<pre>',print_r($record);
            // Validate the data if necessary (Optional)
            $validator = Validator::make($record, [
                'utm_campaign' => 'required|string',
                'utm_term' => 'required|string',
                // Add validation rules for other columns as needed
            ]);

            if ($validator->fails()) {
                $this->error("Validation failed for record: " . json_encode($record));
                continue; // Skip this record and move to the next one
            }

            //$request = new \Illuminate\Http\Request();
            $request = new Request([
                'utm_campaign' => $record['utm_campaign'],
                'utm_term'     => $record['utm_term'],
                'monetization_timestamp' => $record['monetization_timestamp'],
                'revenue' => $record['revenue'],
            ]);

            // Resolve the controller and call the getId method
            $MonetizationEventController = app(MonetizationEventController::class);
            $MonetizationEvent = $MonetizationEventController->store($request);

            if($MonetizationEvent->getStatusCode() == 201)
                $count++;

            if($count == 10000){
                $this->info($count.' rows imported successfully.');
                sleep(5);
                $count = 0;
            }
        }

        $this->info('CSV data imported successfully.');
    }
}
