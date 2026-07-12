<?php

class Gatekeeper
{

    public function getRoute()
    {

        if (isset($_GET['entry'])) {

            return [
                'name' => 'entry',
                'value' => $_GET['entry']
            ];

        }


        if (isset($_GET['page'])) {

            return [
                'name' => 'page',
                'value' => (int) $_GET['page']
            ];

        }


        if (isset($_GET['index'])) {

            return [
                'name' => 'index',
                'value' => $_GET['index']
            ];

        }


        return [
            'name' => 'homepage',
            'value' => null
        ];
    }
}