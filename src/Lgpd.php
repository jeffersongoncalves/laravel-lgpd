<?php

namespace JeffersonGoncalves\Lgpd;

use Closure;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Measure k-anonymity over quasi-identifiers: every combination of their values must be
     * shared by at least k rows. With a sensitive column, l is the smallest number of distinct
     * sensitive values inside a group (l-diversity).
     *
     * ponytail: scans every row in PHP (casts and generalizers apply), so run it off-peak on big tables.
     *
     * @param  class-string<Model>|Builder<Model>  $source
     * @param  array<int|string, string|Closure>  $quasiIdentifiers  columns, or column => generalizer (see Generalize)
     * @return array{rows: int, groups: int, k: int, rows_below_k: int, l: int|null}
     */
    public function kAnonymity(string|Builder $source, array $quasiIdentifiers, int $k = 5, ?string $sensitive = null): array
    {
        $generalizers = [];

        foreach ($quasiIdentifiers as $column => $generalizer) {
            if (is_int($column)) {
                $generalizers[(string) $generalizer] = null;
            } else {
                $generalizers[$column] = $generalizer instanceof Closure ? $generalizer : null;
            }
        }

        $counts = [];
        $values = [];

        foreach ((is_string($source) ? $source::query() : $source)->cursor() as $row) {
            $key = serialize(array_map(
                fn (string $column): mixed => ($generalizers[$column] ?? fn (mixed $value): mixed => $value)($row->getAttribute($column)),
                array_keys($generalizers),
            ));

            $counts[$key] = ($counts[$key] ?? 0) + 1;

            if ($sensitive !== null) {
                $values[$key][serialize($row->getAttribute($sensitive))] = true;
            }
        }

        return [
            'rows' => array_sum($counts),
            'groups' => count($counts),
            'k' => $counts === [] ? 0 : min($counts),
            'rows_below_k' => array_sum(array_filter($counts, fn (int $count): bool => $count < $k)),
            'l' => $sensitive === null || $values === [] ? null : min(array_map('count', $values)),
        ];
    }
}
