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

    public function getDiretorioGlobalImagens()
    {
        return DIR_STATICS . 'public/images';
    }

    public function getDiretorioJsLocal()
    {

    }

    public function getVersao()
    {
        return '1.04.09';
    }

    public function getCaminhoRelatorios()
    {
        return '/circuitos/public/relatorios/';
//        return '/public/relatorios/';
    }
}