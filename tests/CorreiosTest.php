<?php

use pbozzi\correios\Correios;
use PHPUnit\Framework\TestCase;

class CorreiosTest extends TestCase
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

        $this->assertFalse($ret[CorreiosTest::ERROR]);
        $this->assertArrayNotHasKey(CorreiosTest::MESSAGE, $ret);

        $this->assertArrayHasKey(CorreiosTest::ENDERECO, $ret);

        $this->assertArrayHasKey('cep', $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals($cep, $ret[CorreiosTest::ENDERECO]['cep']);
        $this->assertArrayHasKey(CorreiosTest::LOGRADOURO, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('Avenida Paulista', $ret[CorreiosTest::ENDERECO][CorreiosTest::LOGRADOURO]);
        $this->assertArrayHasKey(CorreiosTest::BAIRRO, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('Bela Vista', $ret[CorreiosTest::ENDERECO][CorreiosTest::BAIRRO]);
        $this->assertArrayHasKey(CorreiosTest::CIDADE, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('São Paulo', $ret[CorreiosTest::ENDERECO][CorreiosTest::CIDADE]);
        $this->assertArrayHasKey('uf', $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('SP', $ret[CorreiosTest::ENDERECO]['uf']);
    }

    public function testConsultarCepSuccessoComHifen()
    {
        $cep = '01310-200';
        $ret = Correios::consultarCEP($cep);

        $this->assertFalse($ret[CorreiosTest::ERROR]);
        $this->assertArrayNotHasKey(CorreiosTest::MESSAGE, $ret);

        $this->assertArrayHasKey(CorreiosTest::ENDERECO, $ret);

        $this->assertArrayHasKey('cep', $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals(str_replace('-', '', $cep), $ret[CorreiosTest::ENDERECO]['cep']);
        $this->assertArrayHasKey(CorreiosTest::LOGRADOURO, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('Avenida Paulista', $ret[CorreiosTest::ENDERECO][CorreiosTest::LOGRADOURO]);
        $this->assertArrayHasKey(CorreiosTest::BAIRRO, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('Bela Vista', $ret[CorreiosTest::ENDERECO][CorreiosTest::BAIRRO]);
        $this->assertArrayHasKey(CorreiosTest::CIDADE, $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('São Paulo', $ret[CorreiosTest::ENDERECO][CorreiosTest::CIDADE]);
        $this->assertArrayHasKey('uf', $ret[CorreiosTest::ENDERECO]);
        $this->assertEquals('SP', $ret[CorreiosTest::ENDERECO]['uf']);
    }

    public function testConsultarCepInvalido()
    {
        $cep = 'aaa';
        $ret = Correios::consultarCEP($cep);

        $this->assertTrue($ret[CorreiosTest::ERROR]);
        $this->assertArrayHasKey(CorreiosTest::MESSAGE, $ret);

        $this->assertArrayNotHasKey(CorreiosTest::ENDERECO, $ret);

        $this->assertEquals('CEP inválido', $ret[CorreiosTest::MESSAGE]);
    }

    public function testConsultarCepNaoEncontrado()
    {
        $cep = '99999-999';
        $ret = Correios::consultarCEP($cep);

        $this->assertTrue($ret[CorreiosTest::ERROR]);
        $this->assertArrayHasKey(CorreiosTest::MESSAGE, $ret);

        $this->assertArrayNotHasKey(CorreiosTest::ENDERECO, $ret);

        $this->assertEquals('CEP não encontrado', $ret[CorreiosTest::MESSAGE]);
    }

    public function testConsultarCepExcecaoSoapFault()
    {

        $soapFault = new SoapFault("Receiver", "Unknown error");

        $soapClient = $this->getMockBuilder('SoapClient')
            ->setMethods(array('methodThatCouldThrowSoapFault'))
            ->disableOriginalConstructor()
            ->getMock();

        $soapClient->expects($this->once())
            ->method('methodThatCouldThrowSoapFault')
            ->will($this->throwException($soapFault));

        try
        {
            $soapClient->methodThatCouldThrowSoapFault();
        }
        catch (Exception $e)
        {
            $this->assertSame($e->getMessage(), "Unknown error");
        }
    }
}