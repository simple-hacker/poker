<?php

namespace App\Observers;

use App\Abstracts\Game;

class GameObserver
{
    /**
     * Handle the game "created" event.
     *
     * @param  \App\Abstracts\Game  $game
     * @return void
     */
    public function created(Game $game)
    {
        //
    }

    /**
     * Handle the game "updated" event.
     *
     * @param  \App\Abstracts\Game  $game
     * @return void
     */
    public function updated(Game $game)
    {
        // Get difference between the new $game->profit and the old profit if it's been changed
        if ($game->isDirty('profit')) {
            $game->user->updateBankroll($game->profit - $game->getOriginal('profit'));
        }
    }

    /**
     * Handle the game "deleted" event.
     *
     * @param  \App\Abstracts\Game  $game
     * @return void
     */
    public function deleted(Game $game)
    {
        // We multiple the profit by -1
        // If the Game's profit was positive, need to subtract that amount from the bankroll
        // If the Game's profit was negative, need to add that amount to the bankroll.
        $game->user->updateBankroll($game->profit * -1);

        // Delete all GameTransactions to.
        $game->deleteGameTransactions();
    }

    /**
     * Handle the game "restored" event.
     *
     * @param  \App\Abstracts\Game  $game
     * @return void
     */
    public function restored(Game $game)
    {
        //
    }

    /**
     * Handle the game "force deleted" event.
     *
     * @param  \App\Abstracts\Game  $game
     * @return void
     */
    public function forceDeleted(Game $game)
    {
        //
    }
}