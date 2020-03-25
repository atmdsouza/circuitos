<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09/02/19
 * Time: 11:26
 */

namespace Util;

class Infra
{
    public function getDiretorioCssLocal()
    {

    }

    public function getLimitePaginacao()
    {
        return 100;
    }

    public function getPaginaInicial()
    {
        return 1;
    }

    public function getDiretorioGlobalImagens()
    {
        return DIR_STATICS . 'public/images';
    }

    public function getDiretorioJsLocal()
    {

    }

    public function getSchemaBanco()
    {
        return 'bd_circuitosnavega';
    }

    public function getVersao()
    {
        return '2.04.06';//Melhoria na funcionalidade de Contratos e Proposta Comercial
    }

    public function getCaminhoRelatorios()
    {
//        return '/circuitos/public/relatorios/';
        return '/public/relatorios/';
    }

    public function getCaminhoAnexos()
    {
//        return '/circuitos/public/';
        return '/public/';
    }
}