<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

/**
 * Class: IntegerTransformer
 *
 * @see AbstractTransformer
 */
class IntegerTransformer extends AbstractTransformer
{
    /**
     * transform
     *
     * @param FormInterface $form
     * @param mixed         $extensions
     * @param mixed         $format
     *
     * @return array
     */
    public function transform(FormInterface $form, $extensions = [], $format = null)
    {
        $schema = [
            'type' => 'integer',
        ];
        if ($liform = $form->getConfig()->getOption('liform')) {
            if ($format = $liform['format']) {
                $schema['format'] = $format;
            }
        }
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
