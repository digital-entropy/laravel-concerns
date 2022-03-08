<?php

namespace Dentro\Concerns\Casts;

use Illuminate\Support\Fluent;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EncryptedFluentJson implements CastsAttributes
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
        // suppress exception and try without decrypting the value.
        try {
            $json = json_decode($this->encrypter->decrypt($value), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException $e) {
            $json = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return is_null($json)
            ? new Fluent()
            : new Fluent($json);
    }

    /** {@inheritdoc} */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof Fluent) {
            return $this->encrypter->encrypt($value->toJson());
        }

        $n = new Fluent();
        if (is_array($value)) {
            $n = new Fluent($value);
        }

        return $this->encrypter->encrypt($n->toJson());
    }
}
