
<div class="row">
	<div class="col-md-6 col-sm-12">

		<!-- Our markup, the sdfimportant part here! -->
		<div id="drag-and-drop-zone" class="dm-uploader p-5">
			<h3 class="mb-5 mt-5 text-muted">Drag &amp; drop files here</h3>
			<div class="btn btn-primary btn-block mb-5">
				<span>Open the file Browser</span>
				<input type="file" name="file" title='Click to add Files' />
				
			</div>
		</div><!-- /uploader -->

	</div>
	<div class="col-md-6 col-sm-12">
		<div class="card h-100">
			<div class="card-header">
				File List
			</div>

			<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
				<li class="text-muted text-center empty">No files uploaded.</li>
			</ul>
		</div>
	</div>
</div><!-- /file list -->

<div class="row">
	<div class="col-12">
		<div class="card h-100">
			<div class="card-header">
				Debug Messages
			</div>

			<ul class="list-group list-group-flush" id="debug">
				<li class="list-group-item text-muted empty">Loading plugin....</li>
			</ul>
		</div>
	</div>
</div> <!-- /debug -->