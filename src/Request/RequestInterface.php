<?php

namespace TPG\Broadlink\Request;


use TPG\Broadlink\Session;

interface RequestInterface
{
    public function execute(Session $session);
}