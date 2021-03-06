<?php
/*
 * This file is part of Swagger Mock.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Utility\TestCase;

use App\Mock\Generation\Value\Length\Length;
use App\Mock\Generation\Value\Length\LengthGenerator;
use App\Mock\Generation\Value\ValueGeneratorInterface;
use App\Mock\Generation\ValueGeneratorLocator;
use App\Mock\Parameters\Schema\Type\TypeInterface;
use PHPUnit\Framework\Assert;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
trait ValueGeneratorCaseTrait
{
    /** @var ValueGeneratorLocator */
    protected $valueGeneratorLocator;

    /** @var ValueGeneratorInterface */
    protected $valueGenerator;

    /** @var LengthGenerator */
    protected $lengthGenerator;

    protected function setUpValueGenerator(): void
    {
        $this->valueGeneratorLocator = \Phake::mock(ValueGeneratorLocator::class);
        $this->valueGenerator = \Phake::mock(ValueGeneratorInterface::class);
        $this->lengthGenerator = \Phake::mock(LengthGenerator::class);
    }

    protected function assertValueGeneratorLocator_getValueGenerator_wasCalledOnceWithType(TypeInterface $type): void
    {
        \Phake::verify($this->valueGeneratorLocator)
            ->getValueGenerator($type);
    }

    protected function assertValueGeneratorLocator_getValueGenerator_wasCalledAtMostOnceWithType(TypeInterface $type): void
    {
        \Phake::verify($this->valueGeneratorLocator, \Phake::atMost(1))
            ->getValueGenerator($type);
    }

    protected function givenValueGeneratorLocator_getValueGenerator_returnsValueGenerator(ValueGeneratorInterface $generator = null): void
    {
        if (null === $generator) {
            $generator = $this->valueGenerator;
        }

        \Phake::when($this->valueGeneratorLocator)
            ->getValueGenerator(\Phake::anyParameters())
            ->thenReturn($generator);
    }

    protected function assertValueGenerator_generateValue_wasCalledOnceWithType(TypeInterface $type): void
    {
        \Phake::verify($this->valueGenerator)
            ->generateValue($type);
    }

    protected function assertValueGenerator_generateValue_wasCalledTimesWithType(int $times, TypeInterface $type): void
    {
        \Phake::verify($this->valueGenerator, \Phake::times($times))
            ->generateValue($type);
    }

    protected function assertExpectedValueGenerator_generateValue_wasCalledOnceWithType(
        ValueGeneratorInterface $generator,
        TypeInterface $type
    ): void {
        \Phake::verify($generator)
            ->generateValue($type);
    }

    protected function assertExpectedValueGenerator_generateValue_wasCalledAtMostOnceWithType(
        ValueGeneratorInterface $generator,
        TypeInterface $type
    ): void {
        \Phake::verify($generator, \Phake::atMost(1))
            ->generateValue($type);
    }

    protected function assertValueGenerator_generateValue_wasCalledAtLeastOnceWithType(TypeInterface $type): void
    {
        \Phake::verify($this->valueGenerator, \Phake::atLeast(1))
            ->generateValue($type);
    }

    protected function givenValueGenerator_generateValue_returnsGeneratedValue(): string
    {
        $generatedValue = 'generated_value';

        \Phake::when($this->valueGenerator)
            ->generateValue(\Phake::anyParameters())
            ->thenReturn($generatedValue);

        return $generatedValue;
    }

    protected function givenValueGenerator_generateValue_returnsValue(ValueGeneratorInterface $generator, $value): void
    {
        \Phake::when($generator)
            ->generateValue(\Phake::anyParameters())
            ->thenReturn($value);
    }

    protected function assertValueGeneratorLocator_getValueGenerator_wasCalledOnceWithOneOfTypes(
        TypeInterface $type1,
        TypeInterface $type2
    ): void {
        \Phake::verify($this->valueGeneratorLocator)
            ->getValueGenerator(\Phake::capture($type));
        Assert::assertTrue($type === $type1 || $type === $type2);
    }

    protected function assertValueGenerator_generateValue_wasCalledOnceWithOneOfTypes(
        TypeInterface $type1,
        TypeInterface $type2
    ): void {
        \Phake::verify($this->valueGenerator)
            ->generateValue(\Phake::capture($type));
        Assert::assertTrue($type === $type1 || $type === $type2);
    }

    protected function givenValueGeneratorLocator_getValueGenerator_withType_returnsValueGenerator(TypeInterface $type): ValueGeneratorInterface
    {
        $generator = \Phake::mock(ValueGeneratorInterface::class);

        \Phake::when($this->valueGeneratorLocator)
            ->getValueGenerator($type)
            ->thenReturn($generator);

        return $generator;
    }

    protected function givenLengthGeneratorGeneratesLength(int $length, int $minLength = 0): void
    {
        $arrayLength = new Length();
        $arrayLength->value = $length;
        $arrayLength->minValue = $minLength;

        \Phake::when($this->lengthGenerator)
            ->generateLength(\Phake::anyParameters())
            ->thenReturn($arrayLength);
    }

    protected function assertLengthGeneratedInRange(int $min, int $max): void
    {
        \Phake::verify($this->lengthGenerator)
            ->generateLength($min, $max);
    }
}
