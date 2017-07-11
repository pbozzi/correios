<?php
use pbozzi\correios\Correios;

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

$cep = isset($_GET['cep']) ? $_GET['cep'] : '01310200';

$endereco = Correios::consultarCEPViaCEP($cep);

print_r($endereco);