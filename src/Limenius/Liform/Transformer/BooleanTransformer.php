<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

/**
 * Class: BooleanTransformer
 *
 * @see AbstractTransformer
 */
class BooleanTransformer extends AbstractTransformer
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
        $schema = ['type' => 'boolean'];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
