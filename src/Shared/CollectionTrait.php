<?php
declare(strict_types=1);

namespace PhiSYS\Shared;

trait CollectionTrait
{
    private $items;
    private $current;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Añade un elemento al primer lugar de la colección.
     */
    public function unshift($value): void
    {
        array_unshift($this->items, $value);
        $this->rewind();
    }

    /**
     * Añade un elemento a la colección.
     */
    public function add($value): void
    {
        $this->items[] = $value;
        $this->rewind();
    }

    /**
     * Añade un elemento a la colección si no existe previamente.
     */
    public function addOnly($value): void
    {
        if (false === $this->indexOf($value)) {
            $this->add($value);
        }

        $this->rewind();
    }

    /**
     * Añade una colección entera.
     *
     * @param self $other Otra colección
     */
    public function addCollection(self $other): void
    {
        foreach ($other as $item) {
            $this->add($item);
        }
    }

    /**
     * Añade un array.
     *
     * @param array $arr Array
     * @param bool $only Añade unico
     */
    public function addArray($arr, $only = false)
    {
        foreach ($arr as $item) {
            if ($only) {
                $this->addOnly($item);

                continue;
            }

            $this->add($item);
        }
    }

    /**
     * Establece la colección de items.
     *
     * @param array $arr Colección de items
     */
    public function set(array $arr)
    {
        $this->items = $arr;
        $this->rewind();
    }

    /**
     * Recupera el valor de una key, o el valor por defecto si la key no existe.
     */
    public function get(int $index, $default = null)
    {
        if ($index < 0 && $this->count() >= abs($index)) {
            $index += $this->count();
        }

        if (array_key_exists($index, $this->items)) {
            return $this->items[$index];
        }

        return $default;
    }

    /**
     * Devuelve el array de items.
     *
     * @return array
     */
    public function getArray()
    {
        return $this->items;
    }

    /**
     * Busca la primera posición de un elemento en la colección.
     *
     * @return int|false
     */
    public function indexOf($searchedValue)
    {
        foreach ($this->items as $key => $value) {
            if ($value === $searchedValue) {
                return $key;
            }
        }

        return false;
    }

    /**
     * busca las posiciones de un elemento en la colección.
     *
     * @return array|false
     */
    public function indexesOf($searchedValue)
    {
        $keys = [];

        foreach ($this->items as $key => $value) {
            if ($value !== $searchedValue) {
                continue;
            }

            $keys[] = $key;
        }

        return empty($keys) ? false : $keys; // phpcs:ignore
    }

    /**
     * Elimina todas las posiciones del array con el valor indicado.
     *
     * @throws \Exception
     */
    public function remove($valueToRemove)
    {
        $deleteKeys = $this->indexesOf($valueToRemove);

        for ($i = count($deleteKeys) - 1; $i >= 0; --$i) {
            $this->removeByIndex($deleteKeys[$i]);
        }
    }

    /**
     * Elimina todas las posiciones del array de todos los valores indicados.
     *
     * @param array $items Valores a remover
     * @throws \Exception
     */
    public function removeAll(array $items)
    {
        foreach ($items as $item) {
            $this->remove($item);
        }
    }

    /**
     * Elimina una posición por la key.
     *
     * @param string $index Posición a eliminar
     * @throws \Exception
     */
    public function removeByIndex($index)
    {
        if (!is_integer($index) || !array_key_exists($index, $this->items)) {
            return;
        }

        array_splice($this->items, $index, 1);
    }

    /**
     * Devuelve una copia de la colección.
     *
     * @return static
     */
    public function copy()
    {
        $col = new static();
        $col->addCollection($this);

        return $col;
    }

    /**
     * Comprueba si la colección está vacía.
     *
     * @return bool
     */
    public function isEmpty()
    {
        // phpcs:ignore
        return empty($this->items);
    }

    /**
     * Ejecuta la función a cada item de la colección, para modificar su valor original.
     *
     * @param callable $func Funcion que modificará cada item de la colección. Debe tener la
     *                       forma function($key, $item) { ... }
     */
    public function map(callable $func)
    {
        $args = func_get_args();
        array_splice($args, 0, 1, [0, 0]);

        foreach ($this->items as $key => $item) {
            $args[0] = $key;
            $args[1] = $item;
            $this->items[$key] = call_user_func_array($func, $args);
        }
    }

    /**
     * Recupera el primer elemento que cumpla con el criterio de match (funcion que devuelva true).
     *
     * @param callable $func Funcion que hará el matching con cada item de la colección. Debe tener la
     *                       forma function($key, $item) { ... }
     */
    public function matchFirst(callable $func)
    {
        $args = func_get_args();
        array_splice($args, 0, 1, [0, 0]);

        foreach ($this->items as $key => $item) {
            $args[0] = $key;
            $args[1] = $item;
            $response = call_user_func_array($func, $args);

            if ($response) {
                return $item;
            }
        }

        return false;
    }

    /**
     * Recupera una colección con todos los elementos que cumplan con el criterio de match (funcíon que devuelva true).
     *
     * @param callable $func Funcion que hará el matching con cada item de la colección. Debe tener la
     *                       forma function($key, $item) { ... }
     * @return static
     */
    public function match(callable $func)
    {
        $matches = new static();
        $args = func_get_args();
        array_splice($args, 0, 1, [0, 0]);

        foreach ($this->items as $key => $item) {
            $args[0] = $key;
            $args[1] = $item;
            $response = call_user_func_array($func, $args);

            if (!$response) {
                continue;
            }

            $matches->add($item);
        }

        return $matches;
    }

    /**
     * Vacia la coleccion.
     */
    public function clear()
    {
        $this->reset();
    }

    /**
     * Convierte la colección en una cadena,.
     *
     * @return string
     */
    public function __toString()
    {
        return implode(', ', $this->items);
    }

    /**
     * Resetea la coleccion.
     */
    private function reset(): void
    {
        $this->items = [];
        $this->rewind();
    }
}
