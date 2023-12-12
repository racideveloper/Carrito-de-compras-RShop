$(document).ready(function() {

    // cargar el carrito
    function load_cart() {
        var load_wrapper = $('#load_wrapper'),
        wrapper = $('#cart-wrapper'),
        action = 'get';

        // petición ajax
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data:
            {
                action
            },
            beforeSend: function() {
                load_wrapper.waitMe();
            }
        }).done(function(res) {
            if (res.status === 200) {
                setTimeout(() => {
                    wrapper.html(res.data);
                    load_wrapper.waitMe('hide');
                }, 1500);
            } else {
                swal('Upps!','Ocurrio un error','error');
                wrapper.html('¡Intenta de nuevo, por favor!');
                return true;
            }
        }).fail(function(err) {
            swal('Upps!','Ocurrio un error','error');
            return false;
        }).always(function() {
           
        });
    };
    load_cart();


    // agregar al carrito al dar click en el botón
    $('.do_add_to_cart').on('click', function(e) {
        e.preventDefault();
        var boton = $(this),
        id = $(this).data('id'),
        cantidad = $(this).data('cantidad'),
        action = 'post',
        old_label = boton.html(),
        spinner = '<i class="fas fa-spinner fa-spin"></i>';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: 
            {
                action,
                id,
                cantidad
            },
            beforeSend: function() {
                boton.html(spinner);
            }
        }).done(function(res) {
            if (res.status === 201) {
                swal('¡Bien!','Producto agregado al carrito','success');
                load_cart();
                return;
            } else {
                swal('Upps!',res.msg,'error');
                return;
            }
        }).fail(function(err) {
            swal('Upps!', 'Ocurrió un error','error');
        }).always(function() {
            setTimeout(() => {
                boton.html(old_label);
            }, 1500)
        })
    });

    // actualizar carrito con input
    $('body').on('blur','.do_update_cart', do_update_cart);
    function do_update_cart(e) {
        var input = $(this),
        cantidad = parseInt(input.val()),
        id = input.data('id'),
        action = 'put',
        cant_original = parseInt(input.data('cantidad'));

        // validar que sea un número integro
        if (Math.floor(cantidad) !== cantidad) {
            cantidad = 1;
        }

        // validar que el número ingresado sea mayor a 0 y menos a 99
        if (cantidad < 1) {
            cantidad = 1;
        } else if (cantidad > 99) {
            cantidad = 99;
        }

        if (cantidad === cant_original) return false;

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: 
            {
                action,
                id,
                cantidad
            }
        }).done(function(res) {
            if (res.status === 200) {
                swal('¡Bien!','Producto actualizado','success');
                load_cart();
                return;
            } else {
                swal('Upps!', res.msg, 'error');
                return;
            }
        }).fail(function(err) {
            swal('Upps!', 'Ocurrió un error','error');
        }).always(function() {

        });

    }

    // borrar elemento del carrito
    $('body').on('click', '.do_delete_from_cart', delete_from_cart);
    function delete_from_cart(event) {
        var confirmation,
        id = $(this).data('id'),
        action = 'delete';

        confirmation = confirm('¿Estas seguro?');

        if(!confirmation) return;

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: 
            { 
                action,
                id 
            }
        }).done(function(res) {
            if (res.status === 200) {
                swal('Producto borrado con éxito');
                load_cart();
                return;
            } else {
                swal('Upss!',res.msg, 'error');
                return;
            }
        }).fail(function(err) {
            swal('Upss!','Ocurrio un error, intenta de nuevo', 'error');
        }).always(function() {

        });
    }

    // vaciar carrito
    $('body').on('click', '.do_destroy_cart', destroy_cart);
    function destroy_cart(event) {
        var confirmation,
        action = 'destroy';

        confirmation = confirm('¿Estas seguro?');

        if(!confirmation) return;

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: { action }
        }).done(function(res) {
            if (res.status === 200) {
                swal('Carrito borrado con éxito');
                load_cart();
                return;
            } else {
                swal('Upss!',res.msg, 'error');
                return;
            }
        }).fail(function(err) {
            swal('Upss!','Ocurrio un error, intenta de nuevo', 'error');
        }).always(function() {

        });
    }

    // realizar el pago
    $('body').on('submit','#do_pay', do_pay);
    function do_pay(e) {
        e.preventDefault();
        var form = $(this),
        data = form.serialize(),
        action = 'pay';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: 
            { 
                action,
                data 
            },
            beforeSend: function() {
            }
        }).done(function(res) {
            if (res.status === 200) {
                $('body').waitMe();
                setTimeout(() => {
                    $('body').waitMe('hide');
                    load_cart();
                    load_order_resume();
                }, 4500);
                return;
            } else {
                swal('Upss!',res.msg, 'error');
                return;
            }
        }).fail(function(err) {
            swal('Upss!','Ocurrio un error, intenta de nuevo', 'error');
        }).always(function() {

        });
    }

    // resumen de la compra
    function load_order_resume() {
        var action = 'order_resume';
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: 
            {
                action
            }
        }).done(function(res) {
            if (res.status === 200) {
                $('body').append(res.data);
                $('#order_resume').modal('show');
            }
        }).fail(function(err) {

        });
    }
});