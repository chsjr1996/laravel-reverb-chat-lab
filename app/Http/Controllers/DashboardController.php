<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardController extends Controller
{
    public function show(): Response|ResponseFactory
    {
        return inertia('Dashboard');
    }
}
