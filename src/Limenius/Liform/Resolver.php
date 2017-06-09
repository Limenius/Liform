<?php

namespace Limenius\Liform;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Transformer;
use Limenius\Liform\Transformer\AbstractTransformer;
use Limenius\Liform\Exception\TransformerException;

/**
 * Class: Resolver
 *
 */
class Resolver
{
    private $transformers = [];

    /**
     * @param mixed $formType
     * @param mixed $transformer
     * @param mixed $widget
     */
    public function setTransformer($formType, AbstractTransformer $transformer, $widget = null)
    {
        $this->transformers[$formType] = [
            'transformer' => $transformer,
            'widget' => $widget,
            ];
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function resolve(FormInterface $form)
    {
        $types = FormUtil::typeAncestry($form);

        foreach ($types as $type) {
            if (isset($this->transformers[$type])) {
                return $this->transformers[$type];
            }
        }

        // Perhaps a compound we don't have a specific transformer for
        if (FormUtil::isCompound($form)) {
            return [
                'transformer' => $this->transformers['compound']['transformer'],
                'widget' => null,
            ];
        }

        throw new TransformerException(
            sprintf(
                'Could not find a transformer for any of these types (%s)',
                implode(', ', $types)
            )
        );
    }
}
