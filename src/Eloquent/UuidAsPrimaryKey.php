<?php

namespace Dentro\Concerns\Eloquent;

use Illuminate\Support\Str;

trait UuidAsPrimaryKey
{
    /**
     * Generate UUID as primary key upon creating new record on eloquent model.
     *
     * @return void
     */
    final public static function bootUuidAsPrimaryKey(): void
    {
        static::creating(static function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateUuid();
            }
        });
    }

    /**
     * Generate UUID as primary key when static method failed.
     *
     * @return void
     */
    final public function initializeUuidAsPrimaryKey(): void
    {
        if (empty($this->{$this->getKeyName()})) {
            $this->{$this->getKeyName()} = $this->generateUuid();
        }
    }

    /**
     * Generate Uuid.
     *
     * @return string
     */
    final public function generateUuid(): string
    {
        return Str::orderedUuid();
    }
}
