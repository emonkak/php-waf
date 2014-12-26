<?php

namespace Emonkak\Framework;

use Symfony\Component\HttpFoundation\Request;

interface HttpKernelInterface
{
    public function handle(Request $request);
}
