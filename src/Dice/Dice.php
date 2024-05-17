<?php

namespace App\Dice;

/**
 * Class Dice
 * Repserent a Dice.
 */
class Dice
{
    protected ?int $value;

    /**
     * Constructor for Dice class.
     */
    public function __construct()
    {
        $this->value = null;
    }

    /**
     * Roll the dice and return the rolled value.
     *
     * @return int The value of the dice.
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Get the valye of the dice.
     *
     * @return int|null The value of the dice, or null if dice not rolled yet.
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Get the dice value as a string representation.
     *
     * @return string the string representation fo the dice value.
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
