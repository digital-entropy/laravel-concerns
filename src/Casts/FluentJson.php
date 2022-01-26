<?php

namespace Dentro\Concerns\Casts;

use Illuminate\Support\Fluent;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FluentJson implements CastsAttributes
{
    /** {@inheritdoc} */
    public function get($model, string $key, $value, array $attributes)
    {
        $json = json_decode($value, true);

        return is_null($json)
            ? new Fluent()
            : new Fluent($json);
    }

    /** {@inheritdoc} */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof Fluent) {
            return $value->toJson();
        }

        $n = new Fluent();
        if (is_array($value) && false === $value instanceof Fluent) {
            $n = new Fluent($value);
        }

        return $n->toJson();
    }
}
