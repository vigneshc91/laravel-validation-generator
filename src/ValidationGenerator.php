<?php

namespace Vigneshc91\LaravelValidationGenerator;

use Illuminate\Console\Command;

class ValidationGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-validation:generate
                            {--tables= : List of tables to generate rules, if not specified all the tables will be taken, the list must be comma separated}
                            {--ignore-tables= : List of tables to be ignored}
                            {--ignore-columns= : List of columns to be ignored}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generates validation rule for this application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = [
            'tables' => $this->option('tables') ? explode(',', $this->option('tables')) : [],
            'ignore_tables' => $this->option('ignore-tables') ? explode(',', $this->option('ignore-tables')) : [],
            'ignore_columns' => $this->option('ignore-columns') ? explode(',', $this->option('ignore-columns')) : []
        ];
        $generator = new Generator($options);
        $generator->generate();
    }
}
