## TUNGA CODE CHALLENGE

A program to import data from a large JSON file into a database using PHP in Laravel.

Requirements:

- Clone the repo at [https://github.com/webpriest/tunga-challenge.git](https://github.com/webpriest/tunga-challenge.git).
- Run `composer install` to pull dependencies.
- Run `npm install && npm run dev` for the UI package.
- Change **.env.example** to **.env** and set **DB_DATABASE=tunga_challenge**. 
- Run `php artisan key:generate` to generate the APP_KEY.
- Create database named **tunga_challenge** in MySQL or MariaDB, and run `php artisan migrate` to migrate tables to the just created database.
- Run command `php artisan serve` to boot up the local server.
- Access application with [http://localhost:8000](http://localhost:8000).
- **Open a new CLI and run `php artisan queue:listen` to process all jobs and listen for further jobs _(keep it running for the entire process of testing)_.
- On loaded page in the browser, click the **Choose file** or **Browse** button to select a large JSON file.
- Click **IMPORT JSON** button to upload file for processing *(this may take few seconds, and a notification will be sent to the UI confirming that a queue job is running in the background)*

## Code Implementation

The entry page is found at **resources > views > index.blade.php**. It contains a form with browse and submit buttons. The elements are represented by components created from Laravel Breeze.

It uses web routes located at **routes > web.php*. There are two (2) routes included. 
- A GET route to display the upload form
- A POST route to process the form request

The controller is located at **app > Http > Controllers > ProfileController**. This contains two (2) methods; **index()** and **store()**. The _index_ method returns the view containing the form, while the _store_ method receives and processes the form request.

### Processing the File Upload

A JSON file is sent to the store() method where there is an immediate test to verify that the file was sent. The process is outlined below:

- **JsonMachine** is a Laravel package for parsing unpredictably large JSON files. It parses a json array into the $profiles variable. One of the major advantages of using JsonMachine, is to mitigate the problems associated with uploading unpredictable file sizes. There is no need to adjust the _memory limit_ in php.ini, as this kind of upload may be performed by a client whose action you cannot control. 
- A **try - catch** block is used as a safety net for trying to process the records.
- The _foreach_ loops through every record parsed from the JSON file.
- The records are encoded into JSON and decoded immediately into an associative array for convenient looping.
- Once decoded, it is sent as a job to the queue until all records have been captured from the JSON file. The challenge.json file used in the demo has about 10,000 records which took about 11 seconds to loop through _(depending on speed of the machine)_.
- The method returns a success message to signify _no error_.

## The Background Queue Job

The job is found in **app > Jobs > ImportData.php**. It takes a record as argument and uses the **handle()** method to queue the jobs. It first formats the `date_of_birth` values to a database-friendly format, and determines the _age_ using **Carbon**.

A control structure checks to see that the age is between 18 and 65 years. If true, the record is persisted to the database, otherwise, the record is ignored.

There are two (2) database tables; **profiles** and **credit_cards** tables. A one-to-one relationship exists between the models.

## Best Practices

The SOLID principle was adhered to in completing the task. 
- Each class was created for a single purpose.
- A class can be modified without breaking the code.
- Classes were abstracted as much as possible.
- The workflow implements the DRY principle.

## Task Requirements
- You can mimic job termination using **CTRL + C** in the CLI where the `php artisan queue:listen` is running. Once stopped, re-run the queue:listen command to continue the jobs from where it stopped.
- Only records matching age between 18 and 65 are persisted to the database.

### Bonus
- If the file size grows to a size 500 times the original size, the time complexity remains the same.
- The filter on the _credit card_ to identify three (3) identical digits in sequence, requires a separate function that will be called in the handler to explode every digit of the card number into an array, and compare the digits (using the array pointer to keep track of the last value). It compares the last value with the next and increments a counter. At the end of the loop, the iteration checks if it is three and returns true, otherwise false _(though this was not implemented in the code)_

**Author: Theophilus Aika**

**Slack: Theophilus_Tunga**

"# tunga-challenge" 
