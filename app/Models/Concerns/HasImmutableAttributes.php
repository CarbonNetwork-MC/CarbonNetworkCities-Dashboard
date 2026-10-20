<?php

namespace App\Models\Concerns;

use LogicException;

trait HasImmutableAttributes
{
    /**
     * Reject changes to identifiers that are part of an external contract.
     */
    protected static function bootHasImmutableAttributes(): void
    {
        static::updating(function (self $model): void {
            foreach ($model->immutableAttributes() as $attribute) {
                if ($model->isDirty($attribute)) {
                    throw new LogicException("The {$attribute} attribute is immutable.");
                }
            }
        });
    }

    /**
     * @return list<string>
     */
    abstract protected function immutableAttributes(): array;
}
