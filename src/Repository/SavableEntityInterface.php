<?php

namespace App\Repository;

/** @template T of object */
interface SavableEntityInterface
{
    /**
     * @param T $entity
     */
    public function save(mixed $entity, bool $flush = true): void;

    /**
     * @param T $entity
     */
    public function remove(mixed $entity, bool $flush = true): void;
}