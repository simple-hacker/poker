<?php

namespace App\Policies;

use App\Transactions\Bankroll;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankrollPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the bankroll.
     *
     * @param  \App\User  $user
     * @param  \App\Bankroll  $bankrollTransaction
     * @return mixed
     */
    public function manage(User $user, Bankroll $bankrollTransaction)
    {
        return $user->id === $bankrollTransaction->user_id;
    }
}
