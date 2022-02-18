<?php

namespace Dentro\Concerns\Casts;

use Illuminate\Support\Fluent;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FluentJson implements CastsAttributes
{
    /**
     * {@inheritdoc}
     *
     * @throws \JsonException
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $array = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        return is_null($array)
            ? new Fluent()
            : new Fluent($array);
    }

    /** {@inheritdoc} */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof Fluent) {
            return $value->toJson();
        }

        $n = new Fluent();
        if (is_array($value)) {
            $n = new Fluent($value);
        }

        return $n->toJson();
    }
}
