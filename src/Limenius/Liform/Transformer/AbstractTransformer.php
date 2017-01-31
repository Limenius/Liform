<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

abstract class AbstractTransformer
{
    public abstract function transform(FormInterface $form, $extensions = [], $format = null);

    protected function applyExtensions($extensions, $form, $schema)
    {
        $newSchema = $schema;
        foreach ($extensions as $extension) {
            $newSchema = $extension->apply($form, $newSchema);
        }
        return $newSchema;
    }

    protected function addCommonSpecs($form, $schema, $extensions = [], $format)
    {
        $schema = $this->addLabel($form, $schema);
        $schema = $this->addAttr($form, $schema);
        $schema = $this->addPattern($form, $schema);
        $schema = $this->addDefault($form, $schema);
        $schema = $this->addDescription($form, $schema);
        $schema = $this->addFormat($form, $schema, $format);
        $schema = $this->applyExtensions($extensions, $form, $schema);
        return $schema;
    }


    protected function addDefault($form, $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['placeholder'])) {
                $schema['default'] = $attr['placeholder'];
            }
        }
        return $schema;
    }

    protected function addPattern($form, $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['pattern'])) {
                $schema['pattern'] = $attr['pattern'];
            }
        }
        return $schema;
    }

    protected function addLabel($form, $schema)
    {
        if ($label = $form->getConfig()->getOption('label')) {
            $schema['title'] = $label;
        } else {
            $schema['title'] = $form->getName();
        }
        return $schema;
    }

    protected function addAttr($form, $schema) {
        if ($attr = $form->getConfig()->getOption('attr')) {
            $schema['attr'] = $attr;
        }
        return $schema;
    }

    protected function addDescription($form, $schema) {
        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['description']) && $description = $liform['description']) {
                $schema['description'] = $description;
            }
        }
        return $schema;
    }

    protected function addFormat($form, $schema, $configFormat) {
        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['format']) && $format = $liform['format']) {
                $schema['format'] = $format;
            }
        } elseif ($configFormat) {
            $schema['format'] = $configFormat;
        }
        return $schema;
    }

    protected function isRequired($form)
    {
        return $form->getConfig()->getOption('required');
    }
}
