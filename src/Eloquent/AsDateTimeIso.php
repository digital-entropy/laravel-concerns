<?php

namespace Dentro\Concerns\Eloquent;

trait AsDateTimeIso
{
    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Carbon
     */
    public function asDateTime($value)
    {
        $carbon = parent::asDateTime($value);
        $carbon->format('c');

        return $carbon;
    }
}
