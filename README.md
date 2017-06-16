# Correios

Biblioteca para consulta do CEP nos Correios.

## Instalação

```sh
$ composer require pbozzi/correios

```

## Utilização

```php
$cep = "01310200";
$endereco = Correios->consultaCEP($cep);
```

Ou

```php
$cep = "01310-200";
$endereco = Correios->consultaCEP($cep);
```

## Retorno

```
Array ( 
    [error] => 
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