<?php
	$redirect_uri = URI . '/auth'; 
	$auth_url = 'https://accounts.google.com/o/oauth2/auth';
	$params = array(
		'response_type' => 'code',
		'client_id' => GOOGLE_CLIENT_ID,
		'redirect_uri' => $redirect_uri,
		'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
		'state' => $_SESSION['state'] ?? '',
	);

if (isset($_SESSION['mcs_user'])) {
	echo '<script>location.href ="' . URI . '/wallet";</script>';
}
?>

<script>
        $(document).ready(function() {
            // Función para abrir la ventana emergente
            function openPopup(url, title, w, h) {
                var left = (screen.width/2)-(w/2);
                var top = (screen.height/2)-(h/2);
                return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
            }

            // Evento click para abrir la ventana emergente al hacer clic en un botón
            $('#authButton').click(function() {
                var authUrl = "<?php echo $auth_url; ?>?<?php echo http_build_query($params); ?>";
                openPopup(authUrl, 'Google Auth', 600, 400);
            });
        });
</script>
    <div class="row g-0 flex-fill">
      <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <div class="container container-tight my-5 px-lg-5">
          <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark">
				<img src="<?php echo IMAGE_ICON_LIGHT; ?>"  height="36" alt="DevByBit.com" class="hide-theme-light">
				<img src="<?php echo IMAGE_ICON_DARK; ?>"  height="36" alt="DevByBit.com" class="hide-theme-dark">
			</a>
          </div>
          <h2 class="h3 text-center mb-3">
            <?php echo lang($messages, 'login', 'title'); ?>
          </h2>
          <div class="text-center text-muted mt-3">
            <?php echo lang($messages, 'login', 'subtitle'); ?>
          </div>
		  <hr>
		  <div class="col-12"><a href="#" onclick="location.href='<?php echo $auth_url; ?>?<?php echo http_build_query($params); ?>';" class="btn w-100">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon text-google"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z" /></svg>
                        <?php echo lang($messages, 'login', 'button'); ?>
                      </a></div>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
        <!-- Photo -->
        <div class="bg-cover h-100 min-vh-100" style="background-image: url(https://devbybit.com/demos/tablerio/static/photos/finances-us-dollars-and-bitcoins-currency-money-2.jpg)"></div>
      </div>
    </div>