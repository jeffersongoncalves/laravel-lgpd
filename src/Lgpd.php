<?php

namespace JeffersonGoncalves\Lgpd;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use JeffersonGoncalves\Lgpd\Contracts\HasPersonalData;

class Lgpd
{
    /**
     * Models registered in config('lgpd.models').
     *
     * @return list<class-string<Model&HasPersonalData>>
     */
    public function models(): array
    {
        $models = [];

        foreach ((array) config('lgpd.models', []) as $model) {
            if (! is_string($model) || ! is_subclass_of($model, Model::class) || ! is_subclass_of($model, HasPersonalData::class)) {
                throw new InvalidArgumentException('lgpd.models entries must be Eloquent models implementing '.HasPersonalData::class.', got: '.var_export($model, true));
            }

            $models[] = $model;
        }

        return $models;
    }

    /**
     * Personal data inventory: one row per declared column (feeds ROPA, DSAR and retention).
     *
     * @return list<array{model: class-string, table: string, column: string, category: string, purpose: string|null, legal_basis: string|null, retention: string|null, anonymize: string}>
     */
    public function inventory(): array
    {
        $rows = [];

        foreach ($this->models() as $model) {
            $table = (new $model)->getTable();

            foreach ($model::personalDataFields() as $field) {
                $rows[] = ['model' => $model, 'table' => $table] + $field->toArray();
            }
        }

        return $rows;
    }
}
