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
        // Format date
        $formatted_dob = strftime("%Y-%m-%d", strtotime($this->record['date_of_birth']));
        // Derive age of person
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
            $profile->date_of_birth = $formatted_dob; 
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

    protected function identicalDigits($credit_card)
    {
        $digits = str_split($credit_card);
        $length = count($digits);

        // 4929658516333072

        for($i = 0; $i < $length; $i++) {
            $digit_count = 1;
            for($j = $i+1; $j < $length; $j++) {
                if($digits[$i] === $digits[$j] && ($j < $length)){
                    $digit_count++;   
                }
                
                if(($j+1 < $length) && $digits[$i] === $digits[$j+1]) {
                    $digit_count++;
                    if($digit_count === 3) {
                        return true;
                    } 
                }
            }
        }

        return false;
    }
}
