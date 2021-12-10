<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $record;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $formatted_dob = strftime("%Y-%m-%d", strtotime($this->record['date_of_birth']));
        $age = Carbon::parse($formatted_dob)->diff(Carbon::now())->y;

        if ($age > 17 && $age < 66) {
            // Create instance of a profile
            $profile = new Profile;
            $profile->name = $this->record['name'];
            $profile->address = $this->record['address'];
            $profile->description = $this->record['description'];
            $profile->email = $this->record['email'];
            $profile->interest = $this->record['interest'];
            $profile->account = $this->record['account'];
            $profile->date_of_birth = $formatted_dob; // Format all dates of birth to Y-m-d format
            $profile->checked = $this->record['checked'] ? 1 : 0;
            $profile->save();
    
            // Add credit card record for each user
            $profile->credit_card()->create([
                'name' => $this->record['credit_card']['name'],
                'type' => $this->record['credit_card']['type'],
                'number' => $this->record['credit_card']['number'],
                'expirationDate' => $this->record['credit_card']['expirationDate'],
            ]);
        }
    }
}
