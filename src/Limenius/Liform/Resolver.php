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
                'transformer' => new Transformer\CompoundTransformer($this),
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

    /**
     * Set a sensible choice of default transformers to reduce boilerplate
     * when using this library
     *
     */
    public function setDefaultTransformers()
    {
        $compoundTransformer = new Transformer\CompoundTransformer($this);
        $arrayTransformer = new Transformer\ArrayTransformer($this);
        $integerTransformer = new Transformer\IntegerTransformer();
        $choiceTransformer = new Transformer\ChoiceTransformer();
        $stringTransformer = new Transformer\StringTransformer();
        $numberTransformer = new Transformer\NumberTransformer();
        $booleanTransformer = new Transformer\BooleanTransformer();

        $this->setTransformer('compound', $compoundTransformer);
        $this->setTransformer('integer', $integerTransformer);
        $this->setTransformer('text', $stringTransformer);
        $this->setTransformer('textarea', $stringTransformer, 'textarea');
        $this->setTransformer('password', $stringTransformer, 'password');
        $this->setTransformer('money', $stringTransformer, 'money');
        $this->setTransformer('number', $numberTransformer);
        $this->setTransformer('choice', $choiceTransformer);
        $this->setTransformer('search', $stringTransformer, 'search');
        $this->setTransformer('url', $stringTransformer, 'url');
        $this->setTransformer('checkbox', $booleanTransformer);
        $this->setTransformer('collection', $arrayTransformer);
        $this->setTransformer('money', $stringTransformer, 'money');
        $this->setTransformer('time', $stringTransformer);
        $this->setTransformer('percent', $stringTransformer, 'percent');
        $this->setTransformer('email', $stringTransformer, 'email');
        $this->setTransformer('date', $stringTransformer, 'date');
        $this->setTransformer('datetime', $stringTransformer, 'datetime');
        $this->setTransformer('time', $stringTransformer, 'time');
    }
}
