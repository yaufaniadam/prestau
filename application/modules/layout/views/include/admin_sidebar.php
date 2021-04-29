<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion toggled" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url("admin/pengajuan"); ?>">
		<div class="sidebar-brand-icon">
			<img src="<?= base_url() ?>public/dist/img/logo.png" width="40px" height="" />
		</div>
		<div class="sidebar-brand-text mx-3">SIMKATMAWA</div>
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0">

	<!-- Nav Item - Dashboard -->
	<li class="nav-item" id="menu_dashboard">
		<a class="nav-link" href="<?= base_url("admin/dashboard"); ?>">
			<i class="fas fa-fw fa-tachometer-alt"></i>
			<span>Dashboard</span>
		</a>
	</li>

	<?php if ($this->session->userdata('role') != 3) { ?>

		<li class="nav-item" id="semua_pengajuan">
			<a class="nav-link" href="<?= base_url("admin/pengajuan/index/" . $this->session->userdata('role')); ?>">
				<i class="fas fa-fw fa-envelope"></i>
				<span>Pengajuan</span>
			</a>
		</li>

		<li class="nav-item" id="semua_pengajuan">
			<a class="nav-link" href="<?= base_url("admin/pengajuan/verified"); ?>">
				<i class=" fas fa-fw fa-envelope"></i>
				<span>Lolos Verifikasi</span>
			</a>
		</li>

		<li class="nav-item" id="semua_pengajuan">
			<a class="nav-link" href="<?= base_url("admin/periode/index/1"); ?>">
				<i class="fas fa-fw fa-envelope"></i>
				<span>Periode</span>
			</a>
		</li>

		<li class="nav-item" id="semua_pengajuan">
			<a class="nav-link" href="<?= base_url("admin/pengajuan/arsip/0/0") ?>">
				<i class="fas fa-file-archive"></i>
				<span>Arsip Pengajuan</span>
			</a>
		</li>

		<?php if ($this->session->userdata('role') == 1) { ?>

			<!-- Divider -->
			<hr class="sidebar-divider d-none d-md-block">
			<div class="sidebar-heading">
				Pengaturan
			</div>

			<li class="nav-item" id="menu_pengguna">
				<a class="nav-link" href="<?= base_url("admin/pengguna"); ?>">
					<i class="fas fa-fw fa-users"></i>
					<span>Pengguna</span>
				</a>
			</li>

			<li class="nav-item" id="menu_kategoripengajuan">
				<a class="nav-link" href="<?= base_url("admin/jenispengajuan"); ?>">
					<i class="fas fa-fw fa-list"></i>
					<span>Jenis Pengajuan</span>
				</a>
			</li>

			<li class="nav-item" id="menu_kategoripengajuan">
				<a class="nav-link" href="<?= base_url("admin/jenispengajuan/nominal_penghargaan"); ?>">
					<i class="fas fa-fw fa-list"></i>
					<span>Nominal Reward</span>
				</a>
			</li>

		<?php } ?>

	<?php } ?>


	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>

</ul>
<!-- End of Sidebar -->
