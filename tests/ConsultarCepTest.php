<?php

use pbozzi\correios\Correios;
use PHPUnit\Framework\TestCase;

class ConsultarCepTest extends TestCase
{
    const ERROR = 'error';
    const MESSAGE = 'message';
    const ENDERECO = 'endereco';
    const LOGRADOURO = 'logradouro';
    const BAIRRO = 'bairro';
    const CIDADE = 'cidade';

    public function testConsultarCepSuccesso()
    {
        $cep = '01310200';
        $ret = Correios::consultarCEP($cep);

        $this->assertFalse($ret[ConsultarCepTest::ERROR]);
        $this->assertArrayNotHasKey(ConsultarCepTest::MESSAGE, $ret);

        $this->assertArrayHasKey(ConsultarCepTest::ENDERECO, $ret);

        $this->assertArrayHasKey('cep', $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals($cep, $ret[ConsultarCepTest::ENDERECO]['cep']);
        $this->assertArrayHasKey(ConsultarCepTest::LOGRADOURO, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('Avenida Paulista', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::LOGRADOURO]);
        $this->assertArrayHasKey(ConsultarCepTest::BAIRRO, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('Bela Vista', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::BAIRRO]);
        $this->assertArrayHasKey(ConsultarCepTest::CIDADE, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('São Paulo', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::CIDADE]);
        $this->assertArrayHasKey('uf', $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('SP', $ret[ConsultarCepTest::ENDERECO]['uf']);
    }

    public function testConsultarCepSuccessoComHifen()
    {
        $cep = '01310-200';
        $ret = Correios::consultarCEP($cep);

        $this->assertFalse($ret[ConsultarCepTest::ERROR]);
        $this->assertArrayNotHasKey(ConsultarCepTest::MESSAGE, $ret);

        $this->assertArrayHasKey(ConsultarCepTest::ENDERECO, $ret);

        $this->assertArrayHasKey('cep', $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals(str_replace('-', '', $cep), $ret[ConsultarCepTest::ENDERECO]['cep']);
        $this->assertArrayHasKey(ConsultarCepTest::LOGRADOURO, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('Avenida Paulista', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::LOGRADOURO]);
        $this->assertArrayHasKey(ConsultarCepTest::BAIRRO, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('Bela Vista', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::BAIRRO]);
        $this->assertArrayHasKey(ConsultarCepTest::CIDADE, $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('São Paulo', $ret[ConsultarCepTest::ENDERECO][ConsultarCepTest::CIDADE]);
        $this->assertArrayHasKey('uf', $ret[ConsultarCepTest::ENDERECO]);
        $this->assertEquals('SP', $ret[ConsultarCepTest::ENDERECO]['uf']);
    }

    public function testConsultarCepInvalido()
    {
        $cep = 'aaa';
        $ret = Correios::consultarCEP($cep);

        $this->assertTrue($ret[ConsultarCepTest::ERROR]);
        $this->assertArrayHasKey(ConsultarCepTest::MESSAGE, $ret);

        $this->assertArrayNotHasKey(ConsultarCepTest::ENDERECO, $ret);

        $this->assertEquals('CEP inválido', $ret[ConsultarCepTest::MESSAGE]);
    }

    public function testConsultarCepNaoEncontrado()
    {
        $cep = '99999-999';
        $ret = Correios::consultarCEP($cep);

        $this->assertTrue($ret[ConsultarCepTest::ERROR]);
        $this->assertArrayHasKey(ConsultarCepTest::MESSAGE, $ret);

        $this->assertArrayNotHasKey(ConsultarCepTest::ENDERECO, $ret);

        $this->assertEquals('CEP não encontrado', $ret[ConsultarCepTest::MESSAGE]);
    }
}