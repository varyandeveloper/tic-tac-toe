<?php

namespace App\Controller;

use VS\Response\ResponseInterface;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController
{
    /**
     * @var ResponseInterface $response
     */
    protected $response;

    /**
     * AbstractController constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}