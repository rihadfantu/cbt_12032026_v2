<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class ExamBrowserOnly {
    public function handle(Request $request, Closure $next) {
        $examBrowserOnly = Setting::get('exam_browser_only', '0');
        if ($examBrowserOnly == '1') {
            $ua = $request->userAgent() ?? '';
            $allowed = str_contains($ua, 'SEB') || str_contains($ua, 'Exambro') || str_contains($ua, 'ExamBrowser');
            if (!$allowed) {
                return response()->view('errors.exam_browser', [], 403);
            }
        }
        return $next($request);
    }
}
