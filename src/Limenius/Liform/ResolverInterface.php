<?php

namespace Limenius\Liform;

use Limenius\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormInterface;

interface ResolverInterface
{
    /**
     * @param string               $formType
     * @param TransformerInterface $transformer
     * @param string|null          $widget
     */
    public function setTransformer($formType, TransformerInterface $transformer, $widget = null);

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function resolve(FormInterface $form);
}
