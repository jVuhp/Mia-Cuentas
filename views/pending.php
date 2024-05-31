
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="./" method="get" autocomplete="off" novalidate>
          <div class="card-body text-center">
            <div class="mb-4">
              <h2 class="card-title">Account pending</h2>
              <p class="text-muted">Please wait until an administrator verifies your account and creates your manager.</p>
            </div>
            <div class="mb-4">
              <span class="avatar avatar-xl mb-3" <?php echo (isset($_SESSION['mcs_user']['avatar'])) ? 'style="background-image: url(' . $_SESSION['mcs_user']['avatar'] . ');"' : ''; ?>>
				<?php echo (isset($_SESSION['mcs_user']['avatar'])) ? '' : '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>'; ?>
				
			  </span>
              <h3><?php echo (isset($_SESSION['mcs_user']['name'])) ? $_SESSION['mcs_user']['name'] : 'Unknown'; ?></h3>
              <h4><?php echo (isset($_SESSION['mcs_user']['email'])) ? $_SESSION['mcs_user']['email'] : 'Unknown@gmail.com'; ?></h4>
			  <div>
				<button class="btn btn-ghost-danger" onclick="dropAccount();">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-logout"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
					Logout
				</button>
			  </div>
            </div>
            <div>
            </div>
          </div>
        </form>
      </div>
    </div>