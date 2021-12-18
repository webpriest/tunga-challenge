<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Profile;
use App\Jobs\ImportData;
use Illuminate\Http\Request;
use JsonMachine\JsonMachine;

class ProfileController extends Controller
{
    /**
     * Display upload form
     * 
     * @return void
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Process uploaded file
     * 
     * @return void
     */
    public function store(Request $request)
    {
        
        if($request->hasFile('json_file')) {
            $file = $request->file('json_file');
            if($file->getClientOriginalExtension() == 'json') {
                /*
                - JsonMachine is a fast stream parser for unpredictadbly long JSON streams or documents
                - Parsed file/document maintains consistent memory in spite of growing size (e.g 500 times current size)
                */
                $profiles = JsonMachine::fromFile($file);
                
                try {
                    // Loop through parsed records
                    foreach($profiles as $record) {
                        // JSON encode every record and format with white space and line returns
                        $raw = json_encode($record, JSON_PRETTY_PRINT);
                        // JSON decode in associative array for PHP
                        $record = json_decode($raw, true);
                        // Dispatch job for queues
                        // Job file located at app > Jobs > ImportData.php
                        dispatch(new ImportData($record));
                    }

                    return back()->withSuccess('Data import has been initiated...');
                }
                catch(Throwable $e) {
                    report($e);

                    return false;
                }
            }
            else {
                return back()->withError('Format not supported. Please upload a JSON file');
            }
        }
        else {
            return back()->withError('No file selected. Please choose a file for upload');
        }
    }
}
