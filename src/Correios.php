<?php

namespace pbozzi\correios;

use SoapClient;
use SoapFault;

/**
 * Class Correios
 * @package pbozzi\correios
 */
class Correios
{
    const ERROR = 'error';
    const MESSAGE = 'message';

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

        try {
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
            $result = $client->consultaCep(['cep' => $cep]);

            if (isset($result->return))
            {
                $endereco = array(
                    Correios::ERROR => false,
                    'endereco' => array(
                        'cep' => $result->return->cep,
                        'logradouro' => $result->return->end,
                        'complemento' => $result->return->complemento,
                        'complemento2' => $result->return->complemento2,
                        'bairro' => $result->return->bairro,
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
        catch (SoapFault $e)
        {
            return array(
                Correios::ERROR => true,
                Correios::MESSAGE => $e->getMessage(),
            );
        }
    }
}