<?php

namespace App\Doctrine\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    /**
     * The type name of the doctrine type is configuring.
     */
    abstract public function getEnumClass(): string;

    /**
     * The name of the enum type. This should always include the
     * schema. For example `public.my_enum`;
     */
    abstract public function getPostgresName(): string;

    /**
     * @inheritDoc
     */
    final public function getSQLDeclaration(
        array            $column,
        AbstractPlatform $platform
    ): string {
        return $this->getPostgresName();
    }

    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [$this->getName()];
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $value = is_string($value) ? $value : $value->value;
        if (call_user_func([$this->getEnumClass(), 'tryFrom'], $value)) {
            $value = call_user_func_array([$this->getEnumClass(), 'from'], [$value]);
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
