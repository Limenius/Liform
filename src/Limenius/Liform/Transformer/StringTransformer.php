<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Transformer;

use Limenius\Liform\FormUtil;
use Limenius\Liform\Guesser\ValidatorGuesser;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class StringTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $schema = ['type' => 'string'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addMaxLength($form, $schema);
        $schema = $this->addMinLength($form, $schema);

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    protected function addMaxLength(FormInterface $form, array $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['maxlength'])) {
                $schema['maxLength'] = $attr['maxlength'];
            }
        }

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    protected function addMinLength(FormInterface $form, array $schema)
    {
        if (null === $this->validatorGuesser) {
            return $schema;
        }

        $class = FormUtil::findDataClass($form);

        if (null === $class) {
            return $schema;
        }

        $minLengthGuess = $this->validatorGuesser->guessMinLength($class, $form->getName());
        $minLength = $minLengthGuess ? $minLengthGuess->getValue() : null;

        if ($minLength) {
            $schema['minLength'] = $minLength;
        }

        return $schema;
    }
}
