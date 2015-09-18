<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
class AdminExt {
    public function run($extAction) {
        $responseStatus = '200';
        if ($extAction === 'login') $this->login($extAction);
        return new Response('Расширение Admin работает');
    }
    private function login($extAction) {
        require_once APP . 'ext/components/admin/views/login.php';
    }
}