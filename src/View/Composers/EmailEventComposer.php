<?php

namespace Elfcms\Elfcms\View\Composers;

use Elfcms\Elfcms\Listeners\SomeMailListener;
use Elfcms\Elfcms\Models\User;
use Illuminate\View\View;

class EmailEventComposer
{
    /**
     * The user repository implementation.
     *
     * @var \App\Repositories\UserRepository
     */

    /**
     * Create a new profile composer.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('elfUserData',[
            'userEmail' => $view->emailEvent->eventUser?->email ?? null,
            'userName' => $view->emailEvent->eventUser?->name() ?? null,
            'userFirstName' => $view->emailEvent->eventUser?->data->first_name ?? null,
            'userSecondName' => $view->emailEvent->eventUser?->data->second_name ?? null,
            'userLastName' => $view->emailEvent->eventUser?->data->last_name ?? null,
            'userFormOfAddress' => $view->emailEvent->eventUser?->data->form_of_address->form ?? null,
            'userFormOfAddressLang' => !empty($view->emailEvent?->eventUser->data->form_of_address) ? __('elfcms::default.'.$view->emailEvent->eventUser->data->form_of_address->lang_string) : null,
            'userGenderForm' => $view->emailEvent->eventUser?->data->gender->name ?? null,
            'userGenderFormCode' => $view->emailEvent->eventUser?->data->gender->code ?? null,
            'userGenderFormLang' => !empty($view->emailEvent?->eventUser->data->gender) ? __('elfcms::default.'.$view->emailEvent->eventUser->data->gender->lang_string) : null
        ]);
    }
}
