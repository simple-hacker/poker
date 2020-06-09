<?php

namespace App;

use App\Abstracts\Game;
use App\Exceptions\MultipleBuyInsNotAllowedException;

class Tournament extends Game
{
    protected $guarded = [];

    protected $with = ['variant', 'limit', 'buyIn', 'expenses', 'rebuys', 'addOns', 'cashOut'];

    protected $cascadeDeletes = ['buyIn', 'expenses', 'cashOut', 'rebuys', 'addOns'];

    /**
    * Add a BuyIn for the tournament.
    * This updates the Tournament's profit by subtracting the BuyIn amount.
    * This overwrites the abstract Game->addBuyIn() function because a tournament should only have
    * one buyIn, where as Cash can have multiple
    * 
    * @param float amount
    * @return BuyIn
    */
    public function addBuyIn(float $amount)
    {
        if ($this->buyIn()->count() > 0) {
            throw new MultipleBuyInsNotAllowedException();
        }

        return $this->buyIn()->create([
            'amount' => $amount
        ]);
    }

    /**
    * Add a Rebuy for the tournament.
    * This updates the Tournament's profit by subtracting the Rebuy amount.
    *
    * @param float amount
    * @return Rebuy
    */
    public function addRebuy(float $amount)
    {
        return $this->rebuys()->create([
            'amount' => $amount
        ]);
    }

    /**
    * Add a AddOn for the tournament.
    * This updates the Tournament's profit by subtracting the Rebuy amount.
    *
    * @param float amount
    * @return AddOn
    */
    public function addAddOn(float $amount)
    {
        return $this->addOns()->create([
            'amount' => $amount
        ]);
    }

    /**
    * Returns the Tournament's BuyIn
    * 
    * @return morphMany
    */
    public function buyIn()
    {
        return $this->morphOne('App\Transactions\BuyIn', 'game');
    }

    /**
    * Returns the Tournament's Rebuys
    * 
    * @return morphMany
    */
    public function rebuys()
    {
        return $this->morphMany('App\Transactions\Rebuy', 'game');
    }

    /**
    * Returns the Tournament's Rebuys
    * 
    * @return morphMany
    */
    public function addOns()
    {
        return $this->morphMany('App\Transactions\AddOn', 'game');
    }

    /**
    * Mutate prize_pool in to currency
    *
    * @param Float $prize_pool
    * @return void
    */
    public function getPrizePoolAttribute($prize_pool)
    {
        return $prize_pool / 100;
    }

    /**
    * Mutate prize_pool in to lowest denomination
    *
    * @param Float $prize_pool
    * @return void
    */
    public function setPrizePoolAttribute($prize_pool)
    {
        $this->attributes['prize_pool'] = $prize_pool * 100;
    }
}
