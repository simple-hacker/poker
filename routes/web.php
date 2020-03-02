<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Bankroll Routes
Route::post('/bankroll/add', 'BankrollController@add')->name('bankroll.add')->middleware('auth');
Route::post('/bankroll/withdraw', 'BankrollController@withdraw')->name('bankroll.withdraw')->middleware('auth');
Route::get('/bankroll/{bankrollTransaction}', 'BankrollController@view')->name('bankroll.view')->middleware('auth');
Route::patch('/bankroll/{bankrollTransaction}/update', 'BankrollController@update')->name('bankroll.update')->middleware('auth');
Route::delete('/bankroll/{bankrollTransaction}/delete', 'BankrollController@delete')->name('bankroll.delete')->middleware('auth');


// CashGame Routes
Route::post('/cash/start', 'CashGameController@start')->name('cash.start')->middleware('auth');
Route::get('/cash/current', 'CashGameController@current')->name('cash.current')->middleware('auth');
Route::post('/cash/end', 'CashGameController@end')->name('cash.end')->middleware('auth');
/*
    GET /cash/  View all cash games
    GET /cash/{cash_game}/  View Cash Game
    POST /cash/{cash_game}/  - Create Cash Game?  Not needed because of cash.start we just post data when starting.
    PATCH /cash/{cash_game} - Update details of cash game such as location, SB/BB etc
    DELETE /cash/{cash_game} - Destroy the Cash Game.  Need model observer to update Bankroll
    NOTE:: Need to validate that cash_game belongs to USER
*/

// BuyIn Routes
Route::post('/cash/{cash_game}/buyin/add', 'BuyInController@add')->name('buyin.add')->middleware('auth');
Route::get('/buyin/{buy_in}', 'BuyInController@view')->name('buyin.view')->middleware('auth');
Route::patch('/buyin/{buy_in}', 'BuyInController@update')->name('buyin.update')->middleware('auth');
Route::delete('/buyin/{buy_in}', 'BuyInController@destroy')->name('buyin.delete')->middleware('auth');

// Expenses Routes
Route::post('/cash/{cash_game}/expense/add', 'ExpenseController@add')->name('expense.add')->middleware('auth');
Route::get('/expense/{expense}', 'ExpenseController@view')->name('expense.view')->middleware('auth');
Route::patch('/expense/{expense}', 'ExpenseController@update')->name('expense.update')->middleware('auth');
Route::delete('/expense/{expense}', 'ExpenseController@destroy')->name('expense.delete')->middleware('auth');

// CashOut Routes
Route::post('/cash/{cash_game}/cashout/add', 'CashOutController@add')->name('cashout.add')->middleware('auth');
Route::get('/cashout/{cash_out}', 'CashOutController@view')->name('cashout.view')->middleware('auth');
Route::patch('/cashout/{cash_out}', 'CashOutController@update')->name('cashout.update')->middleware('auth');
Route::delete('/cashout/{cash_out}', 'CashOutController@destroy')->name('cashout.delete')->middleware('auth');

/*
    /cash/{cash_game}/buyin/add
    /cash/{cash_game}/buyin/{buyin}/    GET BUYIN TRANSACTION 
    /cash/{cash_game}/buyin/{buyin}/update
    /cash/{cash_game}/buyin/{buyin}/delete
    Middleware/Policy to check if BuyIn belongs to CashGame and that CashGame belongs to auth User

    /tournament/start
    /tournament/current
    /tournament/{tournament}/end

    











*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
