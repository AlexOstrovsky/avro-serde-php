<?php

namespace FlixTech\AvroSerializer\Test\Objects\SchemaResolvers;

use FlixTech\AvroSerializer\Objects\SchemaResolverInterface;
use FlixTech\AvroSerializer\Objects\SchemaResolvers\ChainResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChainResolverTest extends TestCase
{
    private SchemaResolverInterface|MockObject $chainOne;

    private SchemaResolverInterface|MockObject $chainTwo;

    private ChainResolver $chain;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->chainOne = $this->getMockBuilder(SchemaResolverInterface::class)->getMock();
        $this->chainTwo = $this->getMockBuilder(SchemaResolverInterface::class)->getMock();

        $this->chain = new ChainResolver($this->chainOne, $this->chainTwo);
    }

    /**
     * @throws \AvroSchemaParseException
     */
    #[Test]
    public function it_will_exit_early_when_a_schema_has_been_resolved(): void
    {
        $record = 'I am a record';
        $avroSchema = \AvroSchema::parse('{"type": "string"}');

        $this->chainOne->expects($this->once())
            ->method('valueSchemaFor')
            ->with($record)
            ->willReturn($avroSchema);

        $this->chainTwo->expects($this->never())
            ->method('valueSchemaFor');

        $actual = $this->chain->valueSchemaFor($record);

        $this->assertEquals($avroSchema, $actual);
    }

    /**
     * @throws \AvroSchemaParseException
     */
    #[Test]
    public function it_will_exit_early_when_a_key_schema_has_been_resolved(): void
    {
        $record = 'I am a record';
        $avroSchema = \AvroSchema::parse('{"type": "string"}');

        $this->chainOne->expects($this->once())
            ->method('keySchemaFor')
            ->with($record)
            ->willReturn($avroSchema);

        $this->chainTwo->expects($this->never())
            ->method('keySchemaFor');

        $actual = $this->chain->keySchemaFor($record);

        $this->assertEquals($avroSchema, $actual);
    }

    /**
     * @throws \AvroSchemaParseException
     */
    #[Test]
    public function it_will_call_all_resolvers(): void
    {
        $record = 'I am a record';
        $avroSchema = \AvroSchema::parse('{"type": "string"}');

        $this->chainOne->expects($this->once())
            ->method('valueSchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #1'));

        $this->chainTwo->expects($this->once())
            ->method('valueSchemaFor')
            ->with($record)
            ->willReturn($avroSchema);

        $actual = $this->chain->valueSchemaFor($record);

        $this->assertEquals($avroSchema, $actual);
    }

    /**
     * @throws \AvroSchemaParseException
     */
    #[Test]
    public function it_will_call_all_resolvers_for_key_schemas(): void
    {
        $record = 'I am a record';
        $avroSchema = \AvroSchema::parse('{"type": "string"}');

        $this->chainOne->expects($this->once())
            ->method('keySchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #1'));

        $this->chainTwo->expects($this->once())
            ->method('keySchemaFor')
            ->with($record)
            ->willReturn($avroSchema);

        $actual = $this->chain->keySchemaFor($record);

        $this->assertEquals($avroSchema, $actual);
    }

    #[Test]
    public function it_should_call_all_resolvers_and_throw_for_value_when_no_resolver_has_a_result(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No schema resolver in the chain is able to resolve the schema for the record');
        $record = 'I am a record';

        $this->chainOne->expects($this->once())
            ->method('valueSchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #1'));

        $this->chainTwo->expects($this->once())
            ->method('valueSchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #2'));

        $this->chain->valueSchemaFor($record);
    }

    #[Test]
    public function it_should_call_all_resolvers_and_return_null_when_no_key_resolver_has_a_result(): void
    {
        $record = 'I am a record';

        $this->chainOne->expects($this->once())
            ->method('keySchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #1'));

        $this->chainTwo->expects($this->once())
            ->method('keySchemaFor')
            ->with($record)
            ->willThrowException(new \InvalidArgumentException('I am not thrown #2'));

        $this->assertNull($this->chain->keySchemaFor($record));
    }
}
