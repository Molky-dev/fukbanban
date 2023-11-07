<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

header('Access-Control-Allow-Origin: *');

class Kernel extends BaseKernel
{

    use MicroKernelTrait;
}
