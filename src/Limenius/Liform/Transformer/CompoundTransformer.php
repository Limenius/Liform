<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\FormUtil;
use Limenius\Liform\Resolver;

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
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

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
        $data = [];
        $order = 1;
        $required = [];
        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['format']);
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
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
