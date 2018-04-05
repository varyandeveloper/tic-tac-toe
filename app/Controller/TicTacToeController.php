<?php

namespace App\Controller;

use App\Services\MoveInterface;
use App\Services\MoveService;
use VS\Request\RequestInterface;
use VS\Response\ResponseInterface;

/**
 * Class TicTacToeController
 * @package App\Controller
 */
class TicTacToeController extends AbstractController
{
    /**
     * @var MoveInterface $moveService
     */
    protected $moveService;

    /**
     * TicTacToeController constructor.
     * @param ResponseInterface $response
     * @param MoveService $moveService
     */
    public function __construct(ResponseInterface $response, MoveService $moveService)
    {
        parent::__construct($response);
        $this->moveService = $moveService;
    }

    /**
     * @return \VS\Response\Drivers\View
     */
    public function index()
    {
        return $this->response->view('index/index');
    }

    /**
     * @param RequestInterface $request
     * @return \VS\Response\Drivers\Json
     */
    public function move(RequestInterface $request)
    {
        $boardState = $request->get('boardState');
        $unit = $request->get('unit');

        return $this->response->json($this->moveService->makeMove($boardState, $unit));
    }

    /**
     * @return \VS\Response\Drivers\Json
     */
    public function getBoard()
    {
        return $this->response->json([
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }
}