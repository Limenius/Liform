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
     * @param array         $extensions
     * @param srting|null   $widget
     *
     * @return array
     */
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $schema = ['type' => 'boolean'];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
