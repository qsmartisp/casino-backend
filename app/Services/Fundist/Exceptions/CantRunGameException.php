<?php

namespace App\Services\Fundist\Exceptions;

use App\Services\Fundist\Responses\UserAuthHTMLResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CantRunGameException extends FundistException
{
    protected const MESSAGE = "Can't run game";

    /** @var int $fundistCode */
    private int $fundistCode;

    /** @var string $fundistDescription */
    private string $fundistDescription;

    public function __construct(
        protected UserAuthHTMLResponse $response,
            $message = "",
            $code = 0,
            Throwable $previous = null
    )
    {
        $this->fundistCode = $response->getFundistCode();
        $this->fundistDescription = $this->response->toString();

        $message = $message ?: static::MESSAGE;
        parent::__construct($message . ' (' . $response->toString() . ')', $code, $previous);
    }

    public function render()
    {
        if ($this->fundistCode == 24) {
            return response()->json(['message' => $this->fundistDescription], Response::HTTP_BAD_REQUEST);
        }

        return false;
    }
}
