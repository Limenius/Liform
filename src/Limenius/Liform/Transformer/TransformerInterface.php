<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

interface TransformerInterface
{
    /**
     * @param FormInterface        $form
     * @param ExtensionInterface[] $extensions
     * @param string|null          $widget
     *
     * @return array
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null);
}
