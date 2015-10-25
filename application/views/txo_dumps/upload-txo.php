<?php
@session_start();
$sid = session_id()."_".time();
?>

<style>
#alert{ display: none; }
#console{ display: none; background: rgba(255,0,0,.3);}
#filelist{ min-height: 150px; box-shadow: 0 0 10px #ddd; float: left; padding: 15px; width: 100%;}
.files{ line-height: 30px; }
#popup_container{ border: 0; border-radius: 4px; }
#popup_ok{ padding: 5px 20px; background: #FFF; color: #9d9ea5; border-radius: 4px; border: 1px solid #ccc; }
</style>
<div class="panel panel-default" style="margin: 15px">
	<div class="panel-heading">
		<button class="btn btn-default" id="pickfiles"  style="margin-right: 15px">Select TX0 files</button>
		<span>Choose TX0's to upload.</span>
	</div>
	<div class="panel-body">
		<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
		<div class="col-md-12" style="margin-top: 15px;">
			<pre id="console"></pre>
		</div>
	</div>
	<div class="panel-footer" style="height: 54px;">
		<a class="btn btn-default pull-right" href="<?php echo site_url(); echo $controller; ?>/txo_dumps">Back</a>
		<button class="btn btn-default" id="uploadfiles" style="float: right; margin-right: 10px;">Upload files</button>
	</div>
</div>



<div id="alert">

<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
</div>
<script>
// Custom example logic

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass an id...
	//container: document.getElementById('container'), // ... or DOM Element itself
	url : '<?php echo site_url("media/plupload/upload.php"); ?>',
	flash_swf_url : '<?php echo site_url("media/plupload/js/Moxie.swf"); ?>',
	silverlight_xap_url : '<?php echo site_url("media/js/plupload/Moxie.xap"); ?>',
	chunk_size: '200kb',
	filters : {
		max_file_size : '5mb',
		mime_types: [
			{title : "TX0 Files", extensions : "tx0,tX0"},
			{title : "TX0 Zip Files", extensions : "zip"}
		]
	},

	init: {
		PostInit: function() {
			$('#filelist').html('');
			
			
			$('#uploadfiles').on("click", function(){
				upload_type =  $('select[name="upload-type"]').val();
				console.log(upload_type);
				if(uploader.files.length > 0){
					if(uploader.files.length != uploader.total.uploaded){
						uploader.start();
					}else{
						jAlert('The files are already uploaded. Please select different files', 'Duplicate Files!');
					}
				}else{
					jAlert('There are no files selected.', 'Empty!');
				}
				return false;
			});
		},

		FilesAdded: function(up, files){
			var html='';
			plupload.each(files, function(file) {
				 html += '<div class="col-sm-3 files" id="file-' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b> <a href="javascript:;" class="remove-file" id="rm-file-'+file.id+'"><i class="fa fa-trash" style="color: #FF0000"></i></a></div>';
			});
			$('#filelist').html(html);

			$('.remove-file').on('click', function(){
				var id = $(this).attr('id').replace('rm-file-','');
				$('#file-'+id).remove();
			});
		},

		UploadProgress: function(up, file) {
			$('#file-'+file.id + ' b:first-child').html('<span>' + file.percent + '%</span>');
			$('#rm-file-'+file.id).remove();
		},

		UploadComplete: function(up, files){
			$.post( "<?php echo site_url('txo_dumps/upload_txo_files'); ?>", 
				{data: JSON.stringify(files)});
		},

		Error: function(up, err) {
			$('#console').append("\nError #" + err.code + ": " + err.message).css("display","block");
			//$('#console').fadeOut("slow");
		}
	}
});

uploader.init();

</script>