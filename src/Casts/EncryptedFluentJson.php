<?php

namespace Dentro\Concerns\Casts;

use Exception;
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
        if (empty($value))
            return new Fluent();

        try {
            $json = json_decode($this->encrypter->decrypt($value), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException $e) {
            $json = $this->attemptToDecode($value);
        }

        return new Fluent($json);
    }

    /**
     * attempt to decode normally.
     *
     * @param string $value
     * @return string|null
     */
    private function attemptToDecode(string $value): ?string
    {
        try {
            $json = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $json = null;
        }

        return $json;
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
