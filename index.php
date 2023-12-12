<?php

// todas las funciones estan detras de esto
require_once 'app/config.php';

$data = 
[
    'products' => get_products()
];

// renderizado de la vista
render_view('carrito_view' , $data);