<?php

namespace Dentro\Concerns\Casts;

use Illuminate\Contracts\Encryption\Encrypter;

class EncryptedFluentJson extends FluentJson
{
    /**
     * Encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * EncryptedFluentJson constructor.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->encrypter = app()->make(Encrypter::class);
    }

    /** {@inheritdoc} */
    public function get($model, string $key, $value, array $attributes)
    {
        parent::get($model, $key, $this->encrypter->decrypt($value), $attributes);
    }

    /** {@inheritdoc} */
    public function set($model, string $key, $value, array $attributes)
    {
        parent::set($model, $key, $this->encrypter->encrypt($value), $attributes);
    }
}
