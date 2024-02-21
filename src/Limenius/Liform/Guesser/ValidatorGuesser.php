<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Guesser;

use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class ValidatorGuesser extends ValidatorTypeGuesser
{
    /**
     * {@inheritdoc}
     */
    public function guessMinLength(string $class, string $property)
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessMinLengthForConstraint($constraint);
        });
    }

    /**
     * Guesses a field's minimum length based on the given constraint.
     *
     * @param Constraint $constraint The constraint to guess for
     *
     * @return ValueGuess|null The guess for the minimum length
     */
    public function guessMinLengthForConstraint(Constraint $constraint)
    {
        switch (get_class($constraint)) {
            case Length::class:
                if (is_numeric($constraint->min)) {
                    return new ValueGuess($constraint->min, Guess::HIGH_CONFIDENCE);
                }
                break;
            case Type::class:
                if (in_array($constraint->type, array('double', 'float', 'numeric', 'real'))) {
                    return new ValueGuess(null, Guess::MEDIUM_CONFIDENCE);
                }
                break;
            case Range::class:
                if (is_numeric($constraint->min)) {
                    return new ValueGuess(strlen((string) $constraint->min), Guess::LOW_CONFIDENCE);
                }
                break;
        }

        return null;
    }
}
