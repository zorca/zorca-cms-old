<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
class AdminExt {
    public function run($extAction) {
        return new Response('Расширение Admin работает');
    }
}