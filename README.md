Liform
======

Liform is a library for serializing Symfony Forms into [JSON schema](http://json-schema.org/). It can be used along with [liform-react](https://github.com/Limenius/liform-react) or [json-editor](https://github.com/jdorn/json-editor), or any other form generator based in json-schema.

It is used by [LiformBundle](https://github.com/Limenius/LiformBundle) but can be used stand-alone.

It is very annoying to maintain backend forms that match forms in a client technology, such as JavaScript. It is also annoying to maintain a documentation of such forms. And error prone.

Liform generates a JSON schema representation, that serves as documentation and can be used to document, validate your data and, if you want, to generate forms using a generator.

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this library:

    $ composer require limenius/liform

This command requires you to have Composer installed globally, as explained
in the *installation chapter* of the Composer documentation.

> Liform follows the PSR-4 convention names for its classes, which means you can easily integrate `Liform` classes loading in your own autoloader.


## Usage

Serializing a form into JSON Schema:

```php
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
         "default":"444444",
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

## License

This library is under the MIT license. See the complete license in the file:

    LICENSE.md

## Acknoledgements

The technique for transforming forms using resolvers and reducers is inspired on [Symfony Console Form](https://github.com/matthiasnoback/symfony-console-form)
