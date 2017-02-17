<?php
namespace CodingPhase\Seeder;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

abstract class ModelSeeder extends Seeder
{
    protected $header;

    protected $model;

    protected $amount;

    protected $data;

    protected $compact;

    public function seedModel($model, $tasks, $data = null)
    {
        return $this->setModel($model)->seed($tasks, $data);
    }

    public function seed($tasks, $data = null)
    {
        if ($data) {
            $this->setData($data);
        }

        $this->command->info($this->getHeader());
        $collection = factory($this->getModel(), $this->getAmount())->make();

        $bar = $this->command->getOutput()->createProgressBar(count($collection));

        $collection->each(function ($item, $key) use ($bar, $tasks) {
            $this->fill($item, $key);
            $tasks($item, $key);
            $bar->advance();

            if(! $this->isCompact()) {
                $this->command->line('');
            }
        });

        $bar->finish();
        $this->command->line("");
        $this->command->line("");

        $this->clear();

        return $collection;
    }

    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    private function getHeader()
    {
        if ($this->header) {
            return $this->header;
        }

        return 'Model: ' . $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    private function getModel()
    {
        return $this->model;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    private function getAmount()
    {
        if ($this->amount) {
            return $this->amount;
        }

        return config('seeding.models_amounts.' . $this->getModelConfigName(), config('seeding.default_amount'));
    }

    private function getModelConfigName()
    {
        $collection = collect(explode('\\', mb_strtolower(str_plural($this->getModel()))));

        return $collection->last();
    }

    public function useData($data)
    {
        return $this->setData($data);
    }

    private function setData($data)
    {
        $this->data = collect($data);

        return $this;
    }

    private function getData()
    {
        return $this->data;
    }

    private function fill($item, $key)
    {
        $key = $key + 1;

        if ($this->getData()->has($key)) {
            $item->fill($this->data[$key]);
        }
    }

    private function clear()
    {
        $this->header = null;
        $this->data = collect();
        $this->model = null;
        $this->amount = null;
        $this->compact = true;
    }

    private function isCompact()
    {
        if($this->compact) {
            return $this->compact;
        }

        return config('seeding.compact', true);
    }

    protected function setCompact($compact)
    {
        $this->compact = $compact;

        return $this;
    }
}