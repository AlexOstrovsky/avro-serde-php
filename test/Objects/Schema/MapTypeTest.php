<?php

declare(strict_types=1);

namespace FlixTech\AvroSerializer\Test\Objects\Schema;

use FlixTech\AvroSerializer\Objects\Schema;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MapTypeTest extends TestCase
{
    #[Test]
    public function it_should_serialize_map_types(): void
    {
        $serializedMap = Schema::map()
            ->values(Schema::long())
            ->default(['answer' => 42])
            ->serialize();

        $expectedMap = [
            'type' => 'map',
            'values' => 'long',
            'default' => [
                'answer' => 42,
            ],
        ];

        $this->assertEquals($expectedMap, $serializedMap);
    }

    #[Test]
    public function it_should_parse_map_types(): void
    {
        $parsedSchema = Schema::map()
            ->values(Schema::long())
            ->default(['answer' => 42])
            ->parse();

        $this->assertEquals('map', $parsedSchema->type());
    }
}
