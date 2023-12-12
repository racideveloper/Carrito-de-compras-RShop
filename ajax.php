<?php

require_once 'app/config.php';

//respuesta que regresa a ajax
if (!isset($_POST['action'])) {
	json_output(403);
}

$action = $_POST['action'];

// GET
switch ($action) {
	case 'get':
		$cart = get_cart();
		$output = '';
		if (!empty($cart['products'])) {
			$output .= '
			<div class="table-responsive">
			<table class="table table-hover table-striped table-sm">
			<thead>
				<tr>
					<th>Producto</th>
					<th class="text-center">Precio</th>
					<th class="text-center">Cantidad</th>
					<th class="text-end">Total</th>
					<th class="text-end"></th>
				</tr>
			</thead>
			<tbody>';
			foreach ($cart['products'] as $p) {
				$output .= 
				'<tr>
				<td class="align-middle" width="25%">
					<span class="d-block text-truncate">'.$p['nombre'].'</span>
					<small class="d-block text-muted">SKU '.$p['sku'].'</small>
				</td>
				<td class="align-middle text-center">'.format_currency($p['precio']).'</td>
				<td class="align-middle text-center" width="5%">
					<input data-id="'.$p['id'].'" data-cantidad="'.$p['cantidad'].'" type="text" class="form-control form-control-sm text-center do_update_cart" value="'.$p['cantidad'].'">
				</td>
				<td class="align-middle text-end">'.format_currency(floatval($p['cantidad'] * $p['precio'])).'</td>
				<td class="text-end align-middle">
				<button class="btn btn-sm btn-danger do_delete_from_cart" data-id="'.$p['id'].'">
				    <i class="fas fa-times"></i>
				</button>
				</td>
			    </tr>';
			}
			$output .= 
			    '</tbody>
		         </table>
		         </div>
		         <button class="btn btn-sm btn-danger do_destroy_cart">Vaciar carrito</button>';
		    } else {
			$output .= '
			<div class="text-center py-3">
			<img src="'.IMAGES.'empty-cart.png'.'" title="carrito vacio" class="img-fluid" mb-3 style="width: 80px;">
			<p class="text-muted">Tu carrito está vacío</p>
			</div>';
		}
		$output .= 
		'<br><br>
			<!-- End Cart Content -->

			<!-- Cart Totals-->
			<table class="table mt-3">
				<tr>
				  <th>Subtotal</th>
					<td class="text-end">'.format_currency($cart['cart_totals']['subtotal']).'</td>
			  </tr>
			<tr>
				<th>Envío</th>
				<td class="text-end">'.format_currency($cart['cart_totals']['shipment']).'</td>
			</tr>
			<tr>
				<th>Total</th>
				<td class="text-end">
				<h3 class="fw-bold">'.format_currency($cart['cart_totals']['total']).'</h3>
			</td>
			</tr>
		</table>
		<!-- End Cart Totals -->

		<!-- Payment form -->
            <form id="do_pay" class="py-1">
			    <h4>Añadir método de pago</h4>
                <div class="form-group py-1">
                    <label for="card_name" class="py-2">Nombre en la tarjeta</label>
                    <input type="text" id="card_name" class="form-control" name="card_name" placeholder="Tu nombre">
                </div>
                <div class="form-group py-1">
                    <label for="card_number" class="py-2">Número de tarjeta</label>
                    <input type="text" id="card_number" class="form-control" name="card_number" placeholder="0000 0000 0000 0000">
                </div>
                <div class="form-group py-1 row">
                    <div class="col-xl-6">
                        <label for="card_date" class="py-2">Fecha de expiración</label>
                        <input type="text" id="card_date" class="form-control" name="card_date" placeholder="MM / YY">
                    </div>
                    <div class="col-xl-6">
                        <label for="card_cvc" class="py-2">Código CVC</label>
                        <input type="text" id="card_cvc" class="form-control" name="card_cvc" placeholder="CVC">
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="card_email" class="py-2">E-mail</label>
                    <input type="email" id="card_email" class="form-control" name="card_email" placeholder="Tu correo electrónico">
                </div>
				<button type="submit" class="btn btn-warning btn-lg btn-block w-100 fw-bold">Pagar ahora</button>
            </form>
        <!-- END payment form -->';

		json_output(200, 'OK', $output);
		break;

	// agregar al carrito
	case 'post':
		if (!isset($_POST['id'], $_POST['cantidad'])) {
			json_output(403);
		}

		if (!add_to_cart((int)$_POST['id'], (int)$_POST['cantidad'])) {
			json_output(400, 'Ocurrio un error, intenta de nuevo');
		}

		json_output(201);
		break;
    
	// actualizando productos del carrito
	case 'put':
	    if (!isset($_POST['id'], $_POST['cantidad'])) {
			json_output(403);
		}

		if (!update_cart_product((int)$_POST['id'], (int)$_POST['cantidad'])) {
			json_output(400, 'Ocurrio un error, intenta de nuevo');
		}

		json_output(200);
		break;

	// vaciar el carrito
	case 'destroy':
		if (!destroy_cart()) {
			json_output(400, 'Ocurrio un error, intenta de nuevo');
		}

		json_output(200);
		break;

	// borrar producto del carrito
	case 'delete':
		if (!isset($_POST['id'])) {
			json_output(403);
		}

		if (!delete_from_cart((int)$_POST['id'])) {
			json_output(400, 'Ocurrio un error, intenta de nuevo');
		}

		json_output(200);
		break;
	
	// pagando con la tarjeta
	case 'pay':
		// verificar que existan productos en el carrito
		$cart = get_cart();
		if (empty($cart['products'])) {
			json_output(400, 'No hay productos en el carrito');
		}
		parse_str($_POST['data'],$_POST);
		if (!isset(
			$_POST['card_name'],
			$_POST['card_number'],
			$_POST['card_date'],
			$_POST['card_cvc'],
			$_POST['card_email']
		)) {
			json_output(400, 'Completa todos los campos por favor, intenta de nuevo');
		}

		// tarjeta falsa
		$card = 
		[
			'name'   => 'Pancho Villa',
			'number' => '9052861172943851',
			'month'  => '12',
			'year'   => '25',
			'cvc'    => '035'
		];

		// validación del correo electrónico
		if (!filter_var($_POST['card_email'], FILTER_VALIDATE_EMAIL)) {
			json_output(400, 'Completa todos los campos, intenta de nuevo');
		}

		
		$errors = 0;
		// validación del nombre
		if (clean_string($_POST['card_name']) !== $card['name']) {
			$errors++;
		}

		// validación de número de tarjeta
		if (clean_string(str_replace(' ','',$_POST['card_number'])) !== $card['number']) {
			$errors++;
		}

		// validaión fecha de expiración
		if (!empty($_POST['card_date'])) {
		$date = explode('/',$_POST['card_date']);
		    if (count($date) < 2) {
			$errors++;
		}
		
		if (clean_string($date[0]) !== $card['month']) {
			$errors++;
		}
		if (clean_string($date[1]) !== $card['year']) {
			$errors++;
		}

	    } else {
		$errors++;
	    }

		// validación del cvc
		if (clean_string($_POST['card_cvc']) !== $card['cvc']) {
			$errors++;
		}
	

		// verificar si hay algun error
		if ($errors > 0) {
			json_output(400, 'Verifica la información de tu tarjeta, intenta de nuevo');
		}

		// guardamos la información del carrito para el resumen de la compra
		// número de compra
		$cart['order_number'] = rand(11111111,99999999);
		// información del cliente
		$cart['client'] = $card;
		//guardar resumen de la compra
		$_SESSION['order_resume'] = $cart;

		destroy_cart();
		json_output(200);
		break;

	// resumen de la compra
	case 'order_resume':
		$c = get_order_resume();
		$output = 
		'<!-- Modal -->
		<div class="modal fade" id="order_resume" tabindex="-1" aria-labelledby="order_resume" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h1 class="modal-title fs-5">Resumen de compra</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
			    <div class="text-center py-4">
				   <img src="'.IMAGES.'shopping-basket.png'.'" alt="Resumen de compra" class="img-fluid" style="width: 100px;">
				</div>
				<h3>Gracias por tu compra</h3>
				<h5 class="my-0 fw-bold">No. Autorización: '.$c['order_number'].'</h5>
				Hemos recibido tu pago '.$c['client']['name'].', aquí tenemos el resumen de tu compra:<br><br>
				<table class="table table-hover table-striped table-sm">
			    <thead>
				    <tr>
					    <th>Producto</th>
					    <th class="text-center">Cantidad</th>
					    <th class="text-end">Total</th>
				    </tr>
			    </thead>
			<tbody>';
			foreach ($c['products'] as $p) {
				$output .= 
			   '<tr>
				<td class="align-middle" width="25%">
					<span class="d-block text-truncate">'.$p['nombre'].'</span>
					<small class="d-block text-muted">SKU '.$p['sku'].'</small>
				</td>
				<td class="align-middle text-center"  width="25%">'.$p['cantidad'].'</td>
				<td class="align-middle text-end">'.format_currency(floatval($p['cantidad'] * $p['precio'])).'</td>
			    </tr>';
			}
			  $output .= '
			  <tr>
				<td class="align-middle text-left" colspan="2">Subtotal</td>
				<td class="align-middle text-end" colspan="1">'.format_currency($c['cart_totals']['subtotal']).'</td>
			  </tr>
			  <tr>
				<td class="align-middle text-left" colspan="2">Envío</td>
				<td class="align-middle text-end" colspan="1">'.format_currency($c['cart_totals']['shipment']).'</td>
			  </tr> 
			  <tr>
				<td class="align-middle text-left" colspan="2">Total</td>
				<td class="align-middle text-end" colspan="1">'.format_currency($c['cart_totals']['total']).'</td>
			  </tr> 
			  <tr>
				<td class="align-middle text-left" colspan="2">Forma de pago</td>
				<td class="align-middle text-end" colspan="1">Tarjeta terminación ***'.substr($c['client']['number'],-4).'</td>
			  </tr>
			  <tr>
				<td class="align-middle text-left" colspan="2">Estado del pago</td>
				<td class="align-middle text-end" colspan="1">Aprobado</td>
			  </tr>  
			  </tbody></table>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar</button>
			  </div>
			</div>
		  </div>
		</div>';
		json_output(200,'',$output);
		break;

	default:
	    json_output(403);
		break;
}