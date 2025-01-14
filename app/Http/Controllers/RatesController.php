<?php

namespace App\Http\Controllers;

use App\GraphQL\Queries\InfoQuery;
use App\Mail\StatusMail;
use App\Rules\IsBoolean;
use App\Traits\QueriesFaultyRates;
use App\Traits\ResolvesRates;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class RatesController extends Controller
{
    use QueriesFaultyRates, ResolvesRates;

    public function version0(Request $request): JsonResponse
    {
        try {

            return response()->json($this->getRates($request));

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function version1(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'info' => [new IsBoolean],
            ]);

            $response['USD'] = $this->getRates($request);

            if ($request->string('info', 'true')->toBoolean()) {
                $response['info'] = (new InfoQuery)(null, []);
            }

            if ($request->has('callback')) {
                return response()->jsonp($request->input('callback'),
                    $response,
                );
            } else {
                return response()->json($response);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function status(): StatusMail
    {
        return new StatusMail($this->getFaultyRates());
    }
}
