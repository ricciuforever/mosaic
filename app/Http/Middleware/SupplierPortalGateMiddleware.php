<?php

namespace App\Http\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Models\Setting;

class SupplierPortalGateMiddleware
{
    public function handle(Request $request, string $param = ''): ?Response
    {
        // Attach current supplier portal mode to request for controller/view consumption
        $mode = Setting::get('supplier_portal_mode', 'standby');
        $request->query['_supplier_portal_mode'] = $mode;
        return null;
    }
}
