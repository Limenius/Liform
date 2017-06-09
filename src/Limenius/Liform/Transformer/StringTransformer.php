<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\FormUtil;
use Limenius\Liform\Guesser\ValidatorGuesser;

/**
 * Class: StringTransformer
 *
 * @see AbstractTransformer
 */
class StringTransformer extends AbstractTransformer
{
    /**
     * transform
     *
     * @param FormInterface $form
     * @param array         $extensions
     * @param srting|null   $widget
     *
     * @return array
     */
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $schema = [
            'type' => 'string',
        ];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addMaxLength($form, $schema);
        $schema = $this->addMinLength($form, $schema);

        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addMaxLength($form, $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['maxlength'])) {
                $schema['maxLength'] = $attr['maxlength'];
            }
        }

        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addMinLength($form, $schema)
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
