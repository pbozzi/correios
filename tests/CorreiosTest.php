<?php

use pbozzi\correios\Correios;
use PHPUnit\Framework\TestCase;

class CorreiosTest extends TestCase
{
    public function testConsultaCepSuccesso()
    {
        $cep = '01310200';
        $ret = Correios::consultaCEP($cep);

        $this->assertFalse($ret['error']);
        $this->assertArrayNotHasKey('message', $ret);

        $this->assertArrayHasKey('endereco', $ret);

        $this->assertArrayHasKey('cep', $ret['endereco']);
        $this->assertEquals($cep, $ret['endereco']['cep']);
        $this->assertArrayHasKey('logradouro', $ret['endereco']);
        $this->assertEquals('Avenida Paulista', $ret['endereco']['logradouro']);
        $this->assertArrayHasKey('bairro', $ret['endereco']);
        $this->assertEquals('Bela Vista', $ret['endereco']['bairro']);
        $this->assertArrayHasKey('cidade', $ret['endereco']);
        $this->assertEquals('São Paulo', $ret['endereco']['cidade']);
        $this->assertArrayHasKey('uf', $ret['endereco']);
        $this->assertEquals('SP', $ret['endereco']['uf']);
    }

    public function testConsultaCepSuccessoComHifen()
    {
        $cep = '01310-200';
        $ret = Correios::consultaCEP($cep);

        $this->assertFalse($ret['error']);
        $this->assertArrayNotHasKey('message', $ret);

        $this->assertArrayHasKey('endereco', $ret);

        $this->assertArrayHasKey('cep', $ret['endereco']);
        $this->assertEquals(str_replace('-', '', $cep), $ret['endereco']['cep']);
        $this->assertArrayHasKey('logradouro', $ret['endereco']);
        $this->assertEquals('Avenida Paulista', $ret['endereco']['logradouro']);
        $this->assertArrayHasKey('bairro', $ret['endereco']);
        $this->assertEquals('Bela Vista', $ret['endereco']['bairro']);
        $this->assertArrayHasKey('cidade', $ret['endereco']);
        $this->assertEquals('São Paulo', $ret['endereco']['cidade']);
        $this->assertArrayHasKey('uf', $ret['endereco']);
        $this->assertEquals('SP', $ret['endereco']['uf']);
    }

    public function testConsultaCepInvalido()
    {
        $cep = 'aaa';
        $ret = Correios::consultaCEP($cep);

        $this->assertTrue($ret['error']);
        $this->assertArrayHasKey('message', $ret);

        $this->assertArrayNotHasKey('endereco', $ret);

        $this->assertEquals('CEP inválido', $ret['message']);
    }

    public function testConsultaCepNaoEncontrado()
    {
        $cep = '99999-999';
        $ret = Correios::consultaCEP($cep);

        $this->assertTrue($ret['error']);
        $this->assertArrayHasKey('message', $ret);

        $this->assertArrayNotHasKey('endereco', $ret);

        $this->assertEquals('CEP não encontrado', $ret['message']);
    }

    public function testConsultaCepExcecaoSoapFault()
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