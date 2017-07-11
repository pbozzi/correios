<?php

namespace pbozzi\correios;

use SoapClient;

/**
 * Class Correios
 * @package pbozzi\correios
 */
class Correios
{
    const ERROR = 'error';
    const MESSAGE = 'message';
    const LOGRADOURO = 'logradouro';
    const COMPLEMENTO = 'complemento';
    const BAIRRO = 'bairro';

    /**
     * Consultar o CEP informado via webservice dos Correios.
     *
     * @param string $cep    O número do CEP no formato 99999999 ou 99999-999.
     *
     * @return array         Formato: [ error,
     *                                  message*,
     *                                  endereco*: [ cep,
     *                                               logradouro,
     *                                               complemento,
     *                                               complemento2,
     *                                               bairro,
     *                                               cidade,
     *                                               uf
     *                                             ]
     *                                ]
     */
    final public static function consultarCEP($cep)
    {
        if (!preg_match("/^\d{8}$/", $cep) && !preg_match("/^\d{5}-\d{3}$/", $cep))
        {
            return array(Correios::ERROR => true, Correios::MESSAGE => "CEP inválido");
        }

        $options = array(
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_1,
            'trace' => false,
            'exceptions' => false,
            'connection_timeout' => 180,
            'stream_context' => stream_context_create(
                array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    )
                )
            )
        );

        $client = new SoapClient("https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl", $options);
        $result = @$client->consultaCep(['cep' => $cep]);

        if (isset($result->return))
        {
            $endereco = array(
                Correios::ERROR => false,
                'endereco' => array(
                    'cep' => str_replace('-', '', $result->return->cep),
                    Correios::LOGRADOURO => $result->return->end,
                    Correios::COMPLEMENTO => $result->return->complemento,
                    'complemento2' => $result->return->complemento2,
                    Correios::BAIRRO => $result->return->bairro,
                    'cidade' => $result->return->cidade,
                    'uf' => $result->return->uf,
                )
            );
        }
        else
        {
            $endereco = array(
                Correios::ERROR => true,
                Correios::MESSAGE => 'CEP não encontrado',
            );
        }

        return $endereco;
    }

    /**
     * Consultar o CEP informado via API do ViaCEP.
     *
     * @param string $cep    O número do CEP no formato 99999999 ou 99999-999.
     *
     * @return array         Formato: [ error,
     *                                  message*,
     *                                  endereco*: [ cep,
     *                                               logradouro,
     *                                               complemento,
     *                                               complemento2,
     *                                               bairro,
     *                                               cidade,
     *                                               uf
     *                                             ]
     *                                ]
     */
    final public static function consultarCEPViaCEP($cep)
    {
        if (!preg_match("/^\d{8}$/", $cep) && !preg_match("/^\d{5}-\d{3}$/", $cep)) {
            return array(Correios::ERROR => true, Correios::MESSAGE => "CEP inválido");
        }

        $url = "http://www.viacep.com.br/ws/" . $cep . "/json/unicode";
        $result = Correios::requestCurl($url);

        if ($result && !isset($result['erro']))
        {
            $endereco = array(
                Correios::ERROR => false,
                'endereco' => array(
                    'cep' => str_replace('-', '', $result['cep']),
                    Correios::LOGRADOURO => $result['logradouro'],
                    Correios::COMPLEMENTO => $result['complemento'],
                    'complemento2' => '',
                    Correios::BAIRRO => $result['bairro'],
                    'cidade' => $result['localidade'],
                    'uf' => $result['uf'],
                )
            );
        } else {
            $endereco = array(
                Correios::ERROR => true,
                Correios::MESSAGE => 'CEP não encontrado',
            );
        }

        return $endereco;
    }

    private static function requestCurl($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $dataCurl = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($dataCurl, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return false;
        }

        return $json;
    }
}