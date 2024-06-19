<?php

namespace App\Services;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class WebScraper
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
    }

    public function scrapeTable($url)
    {
        // Datos de inicio de sesión
        $loginUrl = 'https://1at0-mx.teleows.com/portal-web/portal/homepage.html'; // URL de la página de inicio de sesión
        $username = 'E722170';
        $password = 'JQVccip1*';

        // Autenticación
        $crawler = $this->client->request('GET', $loginUrl);
        $form = $crawler->filter('form')->form([
            'username' => $username,
            'password' => $password,
        ]);

        $crawler = $this->client->submit($form, [
            'submit' => 'Log In', // Este valor debe coincidir con el valor del input de submit
        ]);

        // Navegar a la página con la tabla
        $crawler = $this->client->request('GET', $url);

        // Definir los nombres de las columnas
        $columns = [
            'checkbox',
            'image',
            'link',
            'ticket_source',
            'title',
            'site_name',
            'is_vip',
            'suspend_state',
            'sla_hour',
            'task_status',
            'fm_office',
            'empty1',
            'empty2',
            'project',
            'createtime',
            'complete_time',
            'username',
            'actions',
            'gep_acarreo'
        ];

        // Extraer las filas de la tabla
        $rows = $crawler->filter('table.el-table__body tr')->each(function ($node) use ($columns) {
            $data = [];

            $node->filter('td')->each(function ($column, $index) use (&$data, $columns) {
                // Extracción del valor de cada columna
                $text = $column->text();
                $data[$columns[$index]] = trim($text);
            });

            return $data;
        });

        // Eliminar filas vacías
        $rows = array_filter($rows, function ($row) {
            return !empty(array_filter($row));
        });

        return $rows;
    }
}
