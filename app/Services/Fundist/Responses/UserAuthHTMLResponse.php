<?php

namespace App\Services\Fundist\Responses;

use App\Services\Fundist\Response;

class UserAuthHTMLResponse extends Response
{
    public function asArray(): array
    {
        return [
            'content' => $this->getHtml(),
        ];
    }
}
