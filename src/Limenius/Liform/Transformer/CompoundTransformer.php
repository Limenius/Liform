<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\FormUtil;

/**
 * Class: CompoundTransformer
 *
 * @see AbstractTransformer
 */
class CompoundTransformer extends AbstractTransformer
{
    /**
     * __construct
     *
     * @param mixed $resolver
     */
    public function __construct($resolver)
    {
        $this->resolver = $resolver;
    }

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
        $data = [];
        $order = 1;
        $required = [];
        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['widget']);
            $transformedChild['propertyOrder'] = $order;
            $data[$name] = $transformedChild;
            $order ++;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }
        $schema = [
            'title' => $form->getConfig()->getOption('label'),
            'type' => 'object',
            'properties' => $data,
        ];

        if (!empty($required)) {
            $schema['required'] = $required;
        }
        $innerType = $form->getConfig()->getType()->getInnerType();

        if (method_exists($innerType, 'buildLiform')) {
            $schema['liform'] = $innerType->buildLiform($form);
        }
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
