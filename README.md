[![Build Status](https://travis-ci.org/pbozzi/correios.svg?branch=master)](https://travis-ci.org/pbozzi/correios)
[![Coverage Status](https://coveralls.io/repos/github/pbozzi/correios/badge.svg?branch=master)](https://coveralls.io/github/pbozzi/correios?branch=master)
[![Total Downloads](https://poser.pugx.org/pbozzi/correios/downloads)](https://packagist.org/packages/pbozzi/correios)
[![Latest Stable Version](https://poser.pugx.org/pbozzi/correios/v/stable)](https://packagist.org/packages/pbozzi/correios)
[![License](https://poser.pugx.org/pbozzi/correios/license)](https://packagist.org/packages/pbozzi/correios)

# Correios

Biblioteca para consulta do CEP nos Correios.

## Instalação

```sh
$ composer require pbozzi/correios

```

## Utilização

```php
use pbozzi\correios\Correios;
 
...
 
$cep = "01310200"; // ou "01310-200";
$ret = Correios::consultaCEP($cep);
 
if (isset($ret) && $ret['error'] == false)
{
    $data['nme_logradouro'] = $ret['endereco']['logradouro'];
    $data['nme_complemento'] = $ret['endereco']['complemento'];
    $data['nme_complemento2'] = $ret['endereco']['complemento2'];
    $data['nme_bairro'] = $ret['endereco']['bairro'];
    $data['nme_cidade'] = $ret['endereco']['cidade'];
    $data['cod_uf'] = $ret['endereco']['uf'];
 
    return $data;
}
else
{
    return [
        'error' => true,
        'message' => "CEP não encontrado."
    ];
}
```

## Retorno

```
Array ( 
    [error] => false
    [endereco] => Array ( 
        [cep] => 01310200 
        [logradouro] => Avenida Paulista 
        [complemento] => 
        [complemento2] => - de 1512 a 2132 - lado par 
        [bairro] => Bela Vista 
        [cidade] => São Paulo 
        [uf] => SP 
    ) 
)
```

## Requisitos

- PHP >=5.0.1

## Package

https://packagist.org/packages/pbozzi/correios

## Licença

MIT License

Copyright (c) 2017 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
