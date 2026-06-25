<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class ContactController extends Controller
{
    use PageFallback;

    public function index(): View
    {
        $pb = $this->renderFromPageBuilder('contacts');
        if ($pb) return $pb;

        $phone = Setting::get('contacts.phone');
        $email = Setting::get('contacts.email');
        $address = Setting::get('contacts.address');
        $workHours = Setting::get('contacts.work_hours');

        return view('pages.contacts', compact('phone', 'email', 'address', 'workHours'));
    }
}
