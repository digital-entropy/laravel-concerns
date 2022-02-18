<?php

namespace Dentro\Concerns\Eloquent;

trait Lockable
{
    /**
     * Reload a fresh model instance from the database with pessimistic lock enabled.
     *
     * @param array|string $with
     * @return mixed
     */
    public function freshLock(array|string $with = []): mixed
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model $this
         */
        if (! $this->exists) {
            return null;
        }

        return static::newQueryWithoutScopes()
            ->with(is_string($with) ? func_get_args() : $with)
            ->where($this->getKeyName(), $this->getKey())
            ->lockForUpdate()
            ->first();
    }
}
