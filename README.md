Liform
======

Liform is a library for serializing Symfony Forms into [JSON schema](http://json-schema.org/). It can be used along with [liform-react](https://github.com/Limenius/liform-react) or [json-editor](https://github.com/jdorn/json-editor), or any other form generator based on json-schema.

It is used by [LiformBundle](https://github.com/Limenius/LiformBundle) but can also be used as a stand-alone library.

It is very annoying to maintain backend forms that match forms in a client technology, such as JavaScript. It is also annoying to maintain a documentation of such forms. And error prone.

Liform generates a JSON schema representation, that serves as documentation and can be used to document, validate your data and, if you want, to generate forms using a generator.

[![Build Status](https://travis-ci.org/Limenius/Liform.svg?branch=master)](https://travis-ci.org/Limenius/Liform)
[![Latest Stable Version](https://poser.pugx.org/limenius/liform/v/stable)](https://packagist.org/packages/limenius/liform)
[![Latest Unstable Version](https://poser.pugx.org/limenius/liform/v/unstable)](https://packagist.org/packages/limenius/liform)
[![License](https://poser.pugx.org/limenius/liform/license)](https://packagist.org/packages/limenius/liform)

## Installation

Open a console, enter your project directory and execute the
following command to download the latest stable version of this library:

    $ composer require limenius/liform

This command requires you to have Composer installed globally, as explained
in the *installation chapter* of the Composer documentation.

> Liform follows the PSR-4 convention names for its classes, which means you can easily integrate `Liform` classes loading in your own autoloader.


## Usage

Serializing a form into JSON Schema:

```php
use Limenius\Liform\Resolver;
use Limenius\Liform\Liform;
use Limenius\Liform\Liform\Transformer;

$resolver = new Resolver();
$resolver->setTransformer('text', Transformer\StringTransformer);
$resolver->setTransformer('textarea', Transformer\StringTransformer, 'textarea');
// more transformers you might need, for a complete list of what is used in Symfony
// see https://github.com/Limenius/LiformBundle/blob/master/Resources/config/transformers.xml
$liform = new Liform($resolver);

$form = $this->createForm(CarType::class, $car, ['csrf_protection' => false]);
$schema = json_encode($liform->transform($form));
```

And `$schema` will contain a JSON Schema representation such as:

```js
{
   "title":null,
   "properties":{
      "name":{
         "type":"string",
         "title":"Name",
         "propertyOrder":1
      },
      "color":{
         "type":"string",
         "title":"Color",
         "attr":{
            "placeholder":"444444"
         },
         "description":"3 hexadecimal digits",
         "propertyOrder":2
      },
      "drivers":{
         "type":"array",
         "title":"hola",
         "items":{
            "title":"Drivers",
            "properties":{
               "firstName":{
                  "type":"string",
                  "propertyOrder":1
               },
               "familyName":{
                  "type":"string",
                  "propertyOrder":2
               }
            },
            "required":[
               "firstName",
               "familyName"
            ],
            "type":"object"
         },
         "propertyOrder":3
      }
   },
   "required":[
      "name",
      "drivers"
   ]
}
```

## Using your own transformers

Liform works by inspecting the form recursively, finding (resolving) the right transformer for every child and using that transformer to build the corresponding slice of the json-schema. So, if you want to modify the way a particular form type is transformed, you should set a transformer that matches a type with that `block_prefix`.

To do so, you can use the `setTransformer` method of the `Resolver` class. In this case we are reusing the StringTransformer, by overriding the widget property and setting it to `my_widget`, but you could use your very own transformer if you like:

```php

use Limenius\Liform\Liform;

$stringTransformer = $this->get('liform.transformer.string');

$resolver = $this->get('liform.resolver');
$resolver->setTransformer('file', $stringTransformer, 'file_widget');
$liform = new Liform($resolver);
```

## Serializing initial values

This library provides a normalizer to serialize a `FormView` (you can create one with `$form->createView()`) into an array of initial values.

```php
use Limenius\Liform\Serializer\Normalizer\FormViewNormalizer;

$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new FormViewNormalizer());

$serializer = new Serializer($normalizers, $encoders);
$initialValues = $serializer->normalize($form),
```

To obtain an array of initial values that match your json-schema.


## Serializing errors


This library provides a normalizer to serialize forms with errors into an array. This part was shamelessly taken from [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle/blob/master/Serializer/Normalizer/FormErrorNormalizer.php). To use this feature copy the following code in your controller action:

```php
use Limenius\Liform\Serializer\Normalizer\FormErrorNormalizer;

$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new FormErrorNormalizer());

$serializer = new Serializer($normalizers, $encoders);
$initialValues = $serializer->normalize($form),
```

To obtain an array with the errors of your form. [liform-react](https://github.com/Limenius/liform-react), if you are using it, can understand this format.

## Information extracted to JSON-schema

The goal of Liform is to extract as much data as possible from the form in order to have a complete representation with validation and UI hints in the schema. The options currently supported are.

Some of the data can be extracted from the usual form attributes, however, some attributes will be provided using a special `liform` array that is passed to the form options. To do so in a comfortable way a [form extension](http://symfony.com/doc/current/form/create_form_type_extension.html) is provided. See [AddLiformExtension.php](https://github.com/Limenius/Liform/blob/master/src/Limenius/Liform/Form/Extension/AddLiformExtension.php)

### Required

If the field is required (which is the default in Symfony), it will be reflected in the schema.

```php
class DummyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('someText', Type\TextType::class);
    }
}
```

```json
{
   "title":"dummy",
   "type":"object",
   "properties":{
      "someText":{
         "type":"string",
         "title":"someText",
         "propertyOrder":1
      }
   },
   "required":[
      "someText"
   ]
}
```

### Widget

Sometimes you might want to render a field differently then the default behaviour for that type. By using the liform attributes you can specify a particular widget that determines how this field is rendered.


If the attribute `widget` of `liform` is provided, as in the following code:

```php
class DummyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('someText', Type\TextType::class, [
                'liform' => [
                    'widget' => 'my_widget'
                ]
            ]);
    }
}
```

The schema generated will have that `widget` option:
```json
{
   "title":"dummy",
   "type":"object",
   "properties":{
      "someText":{
         "type":"string",
         "widget":"my_widget",
         "title":"someText",
         "propertyOrder":1
      }
   },
   "required":[
      "someText"
   ]
}
```

### Label/Title

If you provide a `label`, it will be used as title in the schema.

```php
class DummyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('someText', Type\TextType::class, [
                'label' => 'Some text',
            ]);
    }
}
```

```json
{
   "title":"dummy",
   "type":"object",
   "properties":{
      "someText":{
         "type":"string",
         "title":"Some text",
         "propertyOrder":1
      }
   },
   "required":[
      "someText"
   ]
}
```

### Pattern

If the attribute `pattern` of `attr` is provided, as in the following code:

```php
class DummyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('someText', Type\TextType::class, [
                'attr' => [
                    'pattern' => '.{5,}',
                ],
            ]);
    }
}

```
It will be extracted as the `pattern` option, so it can be used for validation. Note that, in addition, everything provided to `attr` will be preserved as well.


```json
{
   "title":"dummy",
   "type":"object",
   "properties":{
      "someText":{
         "type":"string",
         "title":"someText",
         "attr":{
            "pattern":".{5,}"
         },
         "pattern":".{5,}",
         "propertyOrder":1
      }
   },
   "required":[
      "someText"
   ]
}
```

### Description

If the attribute `description` of `liform` is provided, as in the following code, it will be extracted in the schema:

```php
class DummyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('someText', Type\TextType::class, [
                'label' => 'Some text',
                'liform' => [
                    'description' => 'This is a help message',
                ]
            ]);
    }
}
```

```json
{
   "title":"dummy",
   "type":"object",
   "properties":{
      "someText":{
         "type":"string",
         "title":"Some text",
         "description":"This is a help message",
         "propertyOrder":1
      }
   },
   "required":[
      "someText"
   ]
}
```

## License

This library is under the MIT license. See the complete license in the file:

    LICENSE.md

## Acknoledgements

The technique for transforming forms using resolvers and reducers is inspired on [Symfony Console Form](https://github.com/matthiasnoback/symfony-console-form)
