<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RabbitCMS\Payments\Factory;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class CallbackAction
 *
 * @package DtKt\LiqPay\Http\Controllers
 */
class CallbackAction extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param Factory                $payments
     * @param ExceptionHandler       $handler
     *
     * @return Response|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, Factory $payments, ExceptionHandler $handler)
    {
        try {
            return $payments->driver((string)Request::route('shop'))->callback($request);
        } catch (Exception $exception) {
            $handler->report($exception);
            return new Response($exception->getMessage(), 500);
        }
    }
}
