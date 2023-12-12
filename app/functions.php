<?php

function get_products() {
    $products = require APP.'products.php';
    return $products;
}

function get_product_by_id($id) {
    $products = get_products();
    foreach ($products as $i => $v) {
        if(intval($v['id']) === (int) $id) {
            return $products[$i];
        }
    }

    return false;
}

// render_view(carrito_view)
function render_view($view , $data = []) {
    if(!is_file(VIEWS.$view.'.php')) {
        // si no existe la vista, entonces se ejecuta esto:
        echo '¡Upss! no existe la vista'. $view;
        die;
    }

    require_once VIEWS.$view.'.php';
}

function format_currency($number, $symbol = '$') {
    if (!is_float($number) && !is_integer($number)) {
        $number = 0;
    }
    return $symbol.number_format($number,2,'.',',');
}



/**
 * FUNCIONES DEL CARRITO
 */
function get_cart() {
    /**
     * products
     * total products
     * subtotal
     * shipment
     * total
     * payment url
     */
    if(isset($_SESSION['cart'])) {
        $_SESSION['cart']['cart_totals'] = calculate_cart_totals();
        return $_SESSION['cart'];
    }

    $cart = 
    [
        'products'       => [],
        'cart_totals'    => calculate_cart_totals(),
        'payment_url'    => NULL
    ];

    $_SESSION['cart'] = $cart;
    return $_SESSION['cart'];
}

function calculate_cart_totals() {

    // el carrito no existe, se inicializa
    // si no hay productos
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
    $cart_totals = 
    [
        'subtotal'       => 0,
        'shipment'       => 0,
        'total'          => 0
    ];
    return $cart_totals;
  }

  // calcular los totales segun los productos en carrito
  $subtotal = 0;
  $shipment = SHIPPING_COST;
  $total    = 0;

  // si hay productos entonces hay que sumar las cantidades
  foreach ($_SESSION['cart']['products'] as $p) {
    $_total = floatval($p['cantidad'] * $p['precio']);
    $subtotal = floatval($subtotal + $_total);
  }

  $total = floatval($subtotal + $shipment);
  $cart_totals = 
    [
        'subtotal'       => $subtotal,
        'shipment'       => $shipment,
        'total'          => $total
    ];
    return $cart_totals;
}

function add_to_cart($id_producto, $cantidad = 1) {
    $new_product = 
    [
        'id'       => NULL,
        'sku'      => NULL,
        'nombre'   => NULL,
        'cantidad' => NULL,
        'precio'   => NULL,
        'imagen'   => NULL
    ];

    $product = get_product_by_id($id_producto);
    // algo paso o no existe el producto
    if (!$product) {
        return false;
    }

    $new_product =
    [
        'id'       => $product['id'],
        'sku'      => $product['sku'],
        'nombre'   => $product['nombre'],
        'cantidad' => $cantidad,
        'precio'   => $product['precio'],
        'imagen'   => $product['imagen']
    ];

    // si no existe el carrito, entonces lo agregamos directamente
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
        $_SESSION['cart']['products'][] = $new_product;
        return true;
      }

    // buscamos un producto con el mismo id
    foreach ($_SESSION['cart']['products'] as $i => $p) {
        if ($id_producto === $p['id']) {
            $p['cantidad']++;
            $_SESSION['cart']['products'][$i] = $p;
            return true;
        }  
    }

    $_SESSION['cart']['products'][] = $new_product;
    return true;
}

function update_cart_product($id_producto, $cantidad = 1) {
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
        return false;
      }

    // buscamos un producto con el mismo id
    foreach ($_SESSION['cart']['products'] as $i => $p) {
        if ($id_producto === $p['id']) {
            $p['cantidad'] = (int) $cantidad;
            $_SESSION['cart']['products'][$i] = $p;
            return true;
        }  
    }

    return false;
}

function delete_from_cart($id_producto) {
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
        return false;
    }

    foreach ($_SESSION['cart']['products'] as $index => $p) {
        if ($id_producto === $p['id']) {
            unset($_SESSION['cart']['products'][$index]);
            return true;
        }  
    }
    return false;
}

function destroy_cart() {
    unset($_SESSION['cart']);
    //session_destroy();
    return true;
}

function json_output($status = 200, $msg = '', $data = []) {
    //http_response_code($status);
    $r = 
    [
        'status' => $status,
        'msg'    => $msg,
        'data'   => $data
    ];
    echo json_encode($r);
    die;
}

function clean_string($string) {
    $string = trim($string);
    $string = rtrim($string);
    $string = ltrim($string);
    return $string;
}

function get_order_resume() {
    if (!isset($_SESSION['order_resume'])) {
        return false;
    }

    return $_SESSION['order_resume'];
}