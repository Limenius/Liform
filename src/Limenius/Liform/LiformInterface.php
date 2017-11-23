<?php

namespace Limenius\Liform;

use Limenius\Liform\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

interface LiformInterface
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function transform(FormInterface $form);

    /**
     * @param ExtensionInterface $extension
     *
     * @return LiformInterface
     */
    public function addExtension(ExtensionInterface $extension);
}
