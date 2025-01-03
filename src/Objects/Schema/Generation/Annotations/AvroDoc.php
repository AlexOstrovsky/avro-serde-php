<?php

declare(strict_types=1);

namespace FlixTech\AvroSerializer\Objects\Schema\Generation\Annotations;

use FlixTech\AvroSerializer\Objects\Schema\AttributeName;
use FlixTech\AvroSerializer\Objects\Schema\Generation\SchemaAttribute;
use FlixTech\AvroSerializer\Objects\Schema\Generation\SchemaAttributes;

/**
 * @Annotation
 */
final class AvroDoc implements SchemaAttribute
{
    public string $value;

    public function name(): string
    {
        return AttributeName::DOC;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function attributes(): SchemaAttributes
    {
        return new SchemaAttributes();
    }
}
