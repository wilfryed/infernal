<?php

class Gatekeeper
{

    public function getRoute()
    {
        if (!isset($_GET['route']) || empty($_GET['route'])) {
            
            return [
                'name' => 'homepage',
                'path' => []
            ];

        }

        return [
            'name' => 'path',
            'path' => explode('/', trim($_GET['route'], '/'))
        ];
    }
}