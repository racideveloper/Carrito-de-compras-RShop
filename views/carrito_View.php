<?php require_once 'includes/inc_header.php' ?>
<?php require_once 'includes/inc_navbar.php' ?>

<!-- Content -->
<div class="container-fluid py-5">
    <div class="row">
        <!-- Products -->
        <div class="col-xl-8">
            <h1>Productos populares</h1>
            <div class="row">
                <?php foreach ($data['products'] as $p): ?>
                    <div class="col-3 mb-3">
                    <div class="card">
                      <img src="<?php echo IMAGES.$p['imagen'] ?>" alt="<?php echo $p['nombre'] ?>" class="card-img-top">
                      <div class="card-body p-2">
                            <h5 class="card-title text-truncate"><?php echo $p['nombre'] ?></h5>
                            <p class="text-danger fw-bold"><?php echo format_currency($p['precio']); ?></p>
                              <button class="btn btn-sm bg-danger text-white fw-bold do_add_to_cart" 
                              data-cantidad="1" data-id="<?php echo $p['id'] ?>" data-bs-toggle="tooltip" data-bs-title="Añadir al carro de la compra"><i class="fas fa-plus"></i> Agregar al carrito</button>
                        </div>
                    </div>
                </div>
              <?php endforeach; ?>
            </div>
        </div>
        <!-- Cart -->
        <div class="col-xl-4 bg-body-tertiary" id="load_wrapper">
            <h1>Mi carrito</h1>
            <!-- Cart Content -->
            <div id="cart-wrapper">
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

<?php require_once 'includes/inc_footer.php' ?>