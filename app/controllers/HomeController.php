<?php

declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $ticketModel = new TicketType();
        $ticketTypes = $ticketModel->allActive();

        $this->render('home', [
            'title' => 'Congreso Digital Future',
            'ticketTypes' => $ticketTypes,
            'styles' => ['home.css'],
            'scripts' => ['home.js'],
        ]);
    }
}
