<?php

namespace Vigneshc91\LaravelValidationGenerator;

use Doctrine\DBAL\Types\Type;
use DB;

class Generator
{
    protected $schemaManager;

    protected $rules;

    protected $options;
    
    /**
     * Initiate global parameters
     * 
     * @param array $options
     */
    public function __construct($options)
    {
        $this->schemaManager = DB::connection()->getDoctrineSchemaManager();
        $this->options = $options;
        $this->rules = [];
    }

    /**
     * Generate the validation for all tables
     *
     * @return void
     */
    public function generate()
    {
        return $this->getAllTables();
    }

    /**
     * Get all tables
     *
     * @return void
     */
    protected function getAllTables()
    {
        if(!empty($this->options['tables'])) {
            foreach ($this->options['tables'] as $table) {
                $this->getAllColumns($table);
            }
        } else {
            $tables = $this->schemaManager->listTables();
            foreach($tables as $table) {
                $this->getAllColumns($table);
            }
        }

        # Convert the array of rules into string
        foreach ($this->rules as $key => $value) {
            foreach ($value as $index => $item) {
                $this->rules[$key][$index] = implode('|', $item);
            }
        }
        return $this->rules;
    }

    /**
     * Get all columns
     *
     * @param mixed $table
     * @return void
     */
    protected function getAllColumns($table)
    {
        if(is_string($table)) {
            $tableName = $table;
            $columns = $this->schemaManager->listTableColumns($table);
        } else {
            $tableName = $table->getName();
            $columns = $table->getColumns();
        }

        if(in_array($tableName, $this->options['ignore_tables'])) {
            return 0;
        }

        $tableName = str_singular($tableName);

        # Get the rules for all the columns
        foreach($columns as $column) {
            $columnName = $column->getName();
            if(!$this->isIgnoreColumn($column) && !in_array($columnName, $this->options['ignore_columns'])) {
                $this->rules[$tableName][$columnName] = $this->getColumnRules($column);
            }
        }
        
    }

    /**
     * Get the column rules for the given column
     *
     * @param Column $column
     * @return array
     */
    protected function getColumnRules($column)
    {
        $rules = $this->getRules($column);
        return $rules;
    }

    /**
     * Check against each type of the column and set the rules
     *
     * @param Column $column
     * @return array
     */
    protected function getRules($column)
    {
        $rule = [];
        
        $type = $column->getType();
        $rule[] = $this->getNullableRule($column);

        switch ($type) {
            case Type::getType('integer'):
                $rule[] = 'integer';
                break;
            case Type::getType('string'):
                $rule[] = 'string';
                if(strpos($column->getName(), 'email') !== false) {
                    $rule[] = 'email';
                }
                $rule[] = 'max:' . $column->getLength();
                break;
            case Type::getType('text'):
                $rule[] = 'string';
                break;
            case Type::getType('date'):
                $rule[] = 'date_format:Y-m-d';
                break;
            case Type::getType('datetime'):
                $rule[] = 'date_format:Y-m-d H:i:s';
                break;
            case Type::getType('time'):
                $rule[] = 'date_format:H:i:s';
                break;
            case Type::getType('float'):
                $rule[] = 'numeric';
                break;
            case Type::getType('json'):
                $rule[] = 'json';
                break;
            case Type::getType('boolean'):
                $rule[] = 'boolean';
                break;
            default:
                # code...
                break;
        }

        return $rule;
    }

    /**
     * Ignore the unwanted columns not to be included in the validaion
     *
     * @param Column $column
     * @return boolean
     */
    protected function isIgnoreColumn($column)
    {
        $name = $column->getName();
        switch ($name) {
            case 'id':
                return 1;
                break;
            case 'created_at':
                return 1;
                break;
            case 'updated_at':
                return 1;
                break;
            case 'deleted_at':
                return 1;
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * Get the nullable of required attribute for the given column
     *
     * @param Column $column
     * @return string
     */
    protected function getNullableRule($column)
    {
        return $column->getNotNull() ? 'required' : 'nullable';
    }


}