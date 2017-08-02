<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Http\Controllers;

use RabbitCMS\Payments\Factory;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
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
     * @param Request          $request
     * @param Factory          $payments
     * @param ExceptionHandler $handler
     *
     * @return Response
     */
    public function __invoke(Request $request, Factory $payments, ExceptionHandler $handler)
    {
        try {
            return $payments->driver($request->route('shop'))->callback($request);
        } catch (Exception $exception) {
            $handler->report($exception);
            return new Response($exception->getMessage(), 500);
        }
    }
}
