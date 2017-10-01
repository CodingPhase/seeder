<?php

namespace CodingPhase\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class ModelSeeder extends Seeder
{
    protected $header;

    protected $model;

    protected $amount;

    protected $data;

    protected $state = null;

    protected $compact = null;

    /**
     * ModelSeeder constructor.
     */
    public function __construct()
    {
        $this->data = collect();
    }

    /**
     * @param $model
     * @param $tasks
     * @param null $data
     * @return mixed
     */
    public function seedModel($model, $tasks, $data = null)
    {
        if ($data != null) {
            $this->setData($data);
        }

        return $this->setModel($model)->seed($tasks);
    }

    /**
     * @param $tasks
     * @return mixed
     */
    public function seed($tasks)
    {
        $this->command->info($this->getHeader());

        if($this->state) {
            $collection = factory($this->getModel(), $this->getAmount())->state($this->getState())->make();
        } else {
            $collection = factory($this->getModel(), $this->getAmount())->make();
        }

        $bar = $this->command->getOutput()->createProgressBar(count($collection));
        $compact = $this->isCompact();

        $collection->each(function ($item, $key) use ($bar, $tasks, $compact) {
            $this->fill($item, $key);
            $tasks($item, $key);
            $bar->advance();

            if ($compact == false) {
                $this->command->line('');
            }
        });

        $this->command->line("");
        $this->command->line("");

        $this->clear();

        return $collection;
    }

    /**
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return string
     */
    private function getHeader()
    {
        if ($this->header) {
            return $this->header;
        }

        return 'Model: ' . $this->model;
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getModel()
    {
        return $this->model;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getAmount()
    {
        if ($this->amount) {
            return $this->amount;
        }

        return config('seeding.models.amounts.' . $this->getModelConfigName(), config('seeding.default_amount'));
    }

    /**
     * @return mixed
     */
    private function getModelConfigName()
    {
        $collection = collect(explode('\\', mb_strtolower(str_plural($this->getModel()))));

        return $collection->last();
    }

    /**
     * @param $data
     * @return ModelSeeder
     */
    public function useData($data)
    {
        return $this->setData($data);
    }

    /**
     * @param $data
     * @return $this
     */
    private function setData($data)
    {
        $this->data = collect($data);

        return $this;
    }

    /**
     * @return mixed
     */
    private function getData()
    {
        return $this->data;
    }

    /**
     * @param $item
     * @param $key
     */
    private function fill($item, $key)
    {
        $key = $key + 1;

        if ($this->getData()->has($key)) {
            $item->fill($this->data[$key]);
        }
    }

    /**
     *
     */
    private function clear()
    {
        $this->header = null;
        $this->data = collect();
        $this->model = null;
        $this->amount = null;
        $this->compact = true;
    }

    /**
     * @return null
     */
    private function isCompact()
    {
        if ($this->compact === null) {
            return config('seeding.compact', true);
        }

        return $this->compact;
    }

    /**
     * @return null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param null $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param $compact
     * @return $this
     */
    protected function setCompact($compact)
    {
        $this->compact = $compact;

        return $this;
    }

    /**
     * @param $tables
     */
    protected function truncate($tables)
    {
        $this->command->info('Truncate:');
        $tables = collect($tables);

        $bar = $this->command->getOutput()->createProgressBar(count($tables));

        Schema::disableForeignKeyConstraints();

        $tables->each(function ($table) use ($bar) {
            DB::table($table)->truncate();
            $bar->advance();
        });

        Schema::enableForeignKeyConstraints();

        $this->command->line("");
        $this->command->line("");
    }
}
