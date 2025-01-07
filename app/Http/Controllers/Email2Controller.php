<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendeMail;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Constants\UserRoles;

class Email2Controller extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('emails.emailForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|min:5|max:100',
            'body' => 'required|string|min:10|max:1000',
            'group' => 'required|string|in:' . implode(',', [UserRoles::ADMIN, UserRoles::NORMAL, UserRoles::ANONYM])
        ]);

        $email_data = [
            'subj' => $request->subject,
            'body' => $request->body
        ];

        if ($request->group === UserRoles::ADMIN) {
            $adminUsers = User::whereHas('role', function ($query) {
                $query->where('name', UserRoles::ADMIN);
            })->get();

            foreach ($adminUsers as $adminUser) {
                Mail::to($adminUser->email)->send(new SendEmail($email_data));
            }
        } elseif ($request->group === UserRoles::NORMAL) {
            $normalUsers = User::whereHas('role', function ($query) {
                $query->where('name', UserRoles::NORMAL);
            })->get();

            foreach ($normalUsers as $normalUser) {
                Mail::to($normalUser->email)->send(new SendEmail($email_data));
            }
        } elseif ($request->group === UserRoles::ANONYM) {
            $anonymUsers = User::whereHas('role', function ($query) {
                $query->where('name', UserRoles::ANONYM);
            })->get();

            foreach ($anonymUsers as $anonymUser) {
                Mail::to($anonymUser->email)->send(new SendEmail($email_data));
            }
        }
        return redirect()->back()->with('success', 'Email sent!');
    }
}
