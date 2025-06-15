<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\ResponseFactory;

class WelcomeController extends Controller
{
    public function show(): Response|ResponseFactory
    {
        return inertia('Welcome');
    }
}
