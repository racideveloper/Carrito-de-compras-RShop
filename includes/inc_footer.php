<!-- Footer -->
<div class="container-fluid bg-dark text-white py-5">
  <footer id="footer">
	<div class="row">
		<div class="col-xl-4">
			  <ul class="list-unstyled">
				  <li><a href="#" class="text-white">Facebook</a></li>
				  <li><a href="#" class="text-white">Instagram</a></li>
				  <li><a href="#" class="text-white">GitHub</a></li>
				  <li><a href="#" class="text-white">Youtube</a></li>
			  </ul>
			</div>
			<div class="col-xl-4">
			  <ul class="list-unstyled">
				  <li><a href="#" class="text-white">Tienda</a></li>
				  <li><a href="#" class="text-white">Carrito</a></li>
				  <li><a href="#" class="text-white">Términos y condiciones</a></li>
			  </ul>
			</div>
			<div class="col-xl-4 text-end">
			  <p>Desarrollado por <a href="#" class="text-warning">RCAcademy</a>.</p>
			</div>
	  </div>
	</footer>
</div>
<!-- End Content-->

<!-- JQuery de Bootstrap 5.3.2 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<!-- CDN Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js"></script>
<!-- Waitme -->
<script src="https://cdn.jsdelivr.net/npm/waitme@1.19.0/waitMe.min.js"></script>

<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

<script>
  $(document).ready(function(){
    $('#card_number').inputmask();
    $('#card_date').inputmask();
  });
</script>

<!-- Main script -->
<script src="assets/js/main.js"></script>

</body>

</html>