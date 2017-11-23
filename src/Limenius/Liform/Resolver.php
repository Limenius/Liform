<?php

namespace Limenius\Liform;

use Limenius\Liform\Exception\TransformerException;
use Limenius\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormInterface;

class Resolver implements ResolverInterface
{
    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param string               $formType
     * @param TransformerInterface $transformer
     * @param string|null          $widget
     */
    public function setTransformer($formType, TransformerInterface $transformer, $widget = null)
    {
        $this->transformers[$formType] = [
            'transformer' => $transformer,
            'widget' => $widget,
        ];
    }

    /**
     * @inheritdoc
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
