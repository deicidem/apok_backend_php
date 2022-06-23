<?php
 
namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
 
class VerifyEmailResponse implements VerifyEmailResponseContract
{
    /**
     * @param  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        return redirect(env('SPA_URL')."?verified=1");
    }
}