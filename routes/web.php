<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Actions\Logout;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('web')->group(function () {
    Route::get('/', fn () => view('welcome'))->name('home');
    Route::post('/logout', Logout::class)->name('logout');
});

use App\Livewire\Dashboard;
Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Livewire\Empleados\EmpleadosTable;
Route::get('/empleados', EmpleadosTable::class)->name('empleados.empleados-table');

use App\Livewire\Asistencias\AsistenciasTable;
Route::get('/asistencias', AsistenciasTable::class)->name('asistencias.asistencias-table');

use App\Livewire\Asistencias\AsistenciasAll;
Route::get('/asistencias/todo', AsistenciasAll::class)->name('asistencias.asistencias-all');

Route::middleware(['auth'])->group(function () {
    Route::get('/asistencias', AsistenciasTable::class)->name('asistencias.asistencias-table');
    Route::get('/empleados', EmpleadosTable::class)->name('empleados.empleados-table');
    Route::get('/asistencias/todo', AsistenciasAll::class)->name('asistencias.asistencias-all');
});




require __DIR__.'/auth.php';
