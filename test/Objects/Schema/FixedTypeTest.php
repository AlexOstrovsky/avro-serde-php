<?php

declare(strict_types=1);

namespace FlixTech\AvroSerializer\Test\Objects\Schema;

use FlixTech\AvroSerializer\Objects\Schema;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FixedTypeTest extends TestCase
{
    #[Test]
    public function it_should_serialize_fixed_types(): void
    {
        $serializedFixedType = Schema::fixed()
            ->namespace('org.acme')
            ->name('md5')
            ->size(16)
            ->aliases('hash', 'fileHash')
            ->serialize();

        $expectedFixedType = [
            'type' => 'fixed',
            'namespace' => 'org.acme',
            'name' => 'md5',
            'size' => 16,
            'aliases' => ['hash', 'fileHash'],
        ];

        $this->assertEquals($expectedFixedType, $serializedFixedType);
    }

    #[Test]
    public function it_should_parse_fixed_types(): void
    {
        $parsedSchema = Schema::fixed()
            ->namespace('org.acme')
            ->name('md5')
            ->size(16)
            ->aliases('hash', 'fileHash')
            ->parse();

        $this->assertEquals('fixed', $parsedSchema->type());
    }
}
