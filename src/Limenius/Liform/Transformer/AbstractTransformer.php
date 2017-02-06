<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

/**
 * Class: AbstractTransformer
 *
 * @abstract
 */
abstract class AbstractTransformer
{
    /**
     * @param FormInterface $form
     * @param array $extensions
     * @param string $widget
     *
     * @return array
     */
    abstract public function transform(FormInterface $form, $extensions = [], $widget = null);

    /**
     * @param array $extensions
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function applyExtensions($extensions, $form, $schema)
    {
        $newSchema = $schema;
        foreach ($extensions as $extension) {
            $newSchema = $extension->apply($form, $newSchema);
        }
        return $newSchema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     * @param array $extensions
     * @param string $widget
     *
     * @return array
     */
    protected function addCommonSpecs($form, $schema, $extensions = [], $widget)
    {
        $schema = $this->addLabel($form, $schema);
        $schema = $this->addAttr($form, $schema);
        $schema = $this->addPattern($form, $schema);
        $schema = $this->addDefault($form, $schema);
        $schema = $this->addDescription($form, $schema);
        $schema = $this->addWidget($form, $schema, $widget);
        $schema = $this->applyExtensions($extensions, $form, $schema);
        return $schema;
    }


    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addDefault($form, $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['placeholder'])) {
                $schema['default'] = $attr['placeholder'];
            }
        }
        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addPattern($form, $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['pattern'])) {
                $schema['pattern'] = $attr['pattern'];
            }
        }
        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addLabel($form, $schema)
    {
        if ($label = $form->getConfig()->getOption('label')) {
            $schema['title'] = $label;
        } else {
            $schema['title'] = $form->getName();
        }
        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addAttr($form, $schema) {
        if ($attr = $form->getConfig()->getOption('attr')) {
            $schema['attr'] = $attr;
        }
        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     *
     * @return array
     */
    protected function addDescription($form, $schema) {
        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['description']) && $description = $liform['description']) {
                $schema['description'] = $description;
            }
        }
        return $schema;
    }

    /**
     * @param mixed $form
     * @param array $schema
     * @param mixed $configWidget
     *
     * @return array
     */
    protected function addWidget($form, $schema, $configWidget) {
        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['widget']) && $widget = $liform['widget']) {
                $schema['widget'] = $widget;
            }
        } elseif ($configWidget) {
            $schema['widget'] = $configWidget;
        }
        return $schema;
    }

    /**
     * @param mixed $form
     *
     * @return boolean
     */
    protected function isRequired($form)
    {
        return $form->getConfig()->getOption('required');
    }
}
