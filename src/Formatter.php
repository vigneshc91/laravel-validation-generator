<?php

namespace Vigneshc91\LaravelValidationGenerator;

class Formatter
{
    protected $rules;

    protected $file;

    protected $destinationFilePath;

    protected $suffix;

    protected $directory;

    protected $config;

    protected $namespace;

    protected $format;

    /**
     * Initiate default values
     *
     * @param array $rules
     * @param array $options
     */
    public function __construct($rules, $options)
    {
        $this->rules = $rules;
        $this->file = __DIR__.'/Request/UserRequest.php';
        $this->suffix = $options['suffix'];
        $this->directory = $options['directory'];
        $this->destinationFilePath = app_path('Http/Requests/' . $this->directory);
        $this->namespace = 'namespace App\Http\Requests' . ($this->directory ? '\\' . $this->directory : '') . ';';
        $this->format = $options['format'];
        $this->createDirectory();
    }   

    /**
     * Format the given input rules
     *
     * @return void
     */
    public function format()
    {
        switch ($this->format) {
            case 'file':
                $this->handleFileFormat();
                break;
            case 'console':
                $this->handleConsoleFormat();
                break;
        }
        
    }

    /**
     * Perform the action for the file format option
     *
     * @return void
     */
    protected function handleFileFormat()
    {
        foreach ($this->rules as $key => $value) {
            $lines = file($this->file, FILE_IGNORE_NEW_LINES);
            $lines[2] = $this->namespace;
            $lines[6] = $this->getClassName($key, $lines[6]);
            $rule = $this->getFormattedRule($lines, $value);
            $this->writeToFile($key, $rule);
        }
    }

    /**
     * Get the class name for the form request file
     *
     * @param string $table
     * @param string $line
     * @return string
     */
    protected function getClassName($table, $line)
    {
        return str_replace('UserRequest', ucfirst(camel_case($table)) . $this->suffix, $line);
    }

    /**
     * Perform the action for the console format option
     *
     * @return void
     */
    protected function handleConsoleFormat()
    {
        print_r($this->rules);
    }

    /**
     * Get the formatted rules for printing into the file
     *
     * @param array $lines
     * @param array $rule
     * @return void
     */
    protected function getFormattedRule($lines, $rule)
    {
        $rule = json_encode($rule);
        $rule = str_replace(['{', '}'], '', $rule);
        $rule = "\t\t\t".$rule;
        $rule = str_replace('":', '" => ', $rule);
        $rule = str_replace(',', ",\n\t\t\t", $rule);
        $rule = array_merge(array_slice($lines, 0, 26) , [$rule] , array_slice($lines, 27));
        return $rule;
    }

    /**
     * Write the string into the file
     *
     * @param string $table
     * @param string $rule
     * @return void
     */
    protected function writeToFile($table, $rule)
    {
        $fileName = $this->destinationFilePath . '/' . ucfirst(camel_case($table)) . $this->suffix . '.php';
        $file = fopen($fileName, 'w');
        foreach ($rule as $index => $value) {
            fwrite($file, $value.PHP_EOL);
        }
        fclose($file);

        echo "\033[32m". basename($fileName). ' Created Successfully'. PHP_EOL;
    }


    /**
     * Create a new directory if not exist
     *
     * @return void
     */
    protected function createDirectory()
    {
        $dirName = $this->destinationFilePath;
        if(!is_dir($dirName)) {
            mkdir($dirName, 0755, true);
        }
    }


}