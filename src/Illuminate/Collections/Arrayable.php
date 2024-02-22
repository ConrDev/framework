<?php

namespace Illuminate\Support;

use ArrayAccess;
use Generator;
use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Contracts\Support\CanBeEscapedWhenCastToString;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Traits\EnumeratesValues;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements \Illuminate\Support\Enumerable<TKey, TValue>
 *
 * @extends \Illuminate\Support\Arr<TKey, TValue>
 */
class Arrayable implements ArrayAccess, Enumerable, CanBeEscapedWhenCastToString
{
    /**
     * @use \Illuminate\Support\Traits\EnumeratesValues<TKey, TValue>
     */
    use EnumeratesValues, Macroable;

    /**
     * The underlying array.
     */
    protected array $array;

    /**
     * Create a new instance of the class.
     */
    public function __construct($array = [])
    {
        $this->array = $this->getArrayableItems($array);
    }

    /**
     * Create a new instance of the class.
     *
     * @param array $array
     * @return Arrayable
     */
    public static function from(array $array = []): static
    {
        return self::make($array);
    }

    public static function __callStatic($method, $parameters): static
    {
        return static::from(call(Arr::class, $method, $parameters));
    }

    public function __call($method, $parameters): mixed
    {
        $calledMethod = null;

        if (method_exists(Arr::class, $method)) {
            $calledMethod = call(Arr::class, $method, array_merge([$this->array], $parameters));

            if (is_array($calledMethod)) {
                return static::from($calledMethod);
            }

            if ($calledMethod instanceof Generator) {
                return static::from(iterator_to_array($calledMethod));
            }
        }

        return $calledMethod;
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param TKey $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->array[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param TKey $key
     * @return TValue
     */
    public function offsetGet($key): mixed
    {
        return $this->array[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param TKey|null $key
     * @param TValue $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->array[] = $value;
        } else {
            $this->array[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param TKey $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->array[$key]);
    }
}
