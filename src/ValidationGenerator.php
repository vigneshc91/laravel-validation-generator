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
                            {--ignore-columns= : List of columns to be ignored}
                            {--dir= : Directory to which the request file are to be stored within the requests folder}
                            {--suffix= : Suffix to append to the request file name}
                            {--format=file : The format you want the validation as the available options are file and console}';

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
        $rules = $generator->generate();

        $formatterOptions = [
            'suffix' => $this->option('suffix') ? $this->option('suffix') : 'CreateUpdate',
            'directory' => $this->option('dir') ? $this->option('dir') : '',
            'format' => $this->option('format')
        ];
        $formatter = new Formatter($rules, $formatterOptions);
        $formatter->format();
    }
}
