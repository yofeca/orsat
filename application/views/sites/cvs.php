							<script>
							$(function(){

								$('#add-cvs-modal').on('show.bs.modal', function(event){
									var button = $(event.relatedTarget);
									cvs.id = button.data('option');
									if(cvs.id>0){
										cvs.edit(cvs.id);
									}else{
										cvs.init();
									}
								});
								$('#save-cvs').on("click", function(){
									cvs.save();
								});
							});

								var cvs = {
									id: 0,
									site_id: 0,
									site_name: "",
									init: function(){
										$('input[name="date_on"]').val('');
										$('input[name="date_off"]').val('');
										$('select[name="cylinder_id"]').val('').trigger("chosen:updated");
										$('input[name="dilution_factor"]').val('');
									},
									save: function(){
										var formdata = $("#cvs-cylinder-form").serialize();
										var target = '';
										console.log(formdata);
										if(this.id>0) target = 'update_cvs_standard/'+this.id;
										else target = 'add_cvs_standard/'+this.id;

										$.post(
											'<?php echo site_url(); echo $controller; ?>/ajax_'+target,
											{ 'data': formdata }
										).done(function(){
											self.location = "<?php echo site_url(); echo $controller; ?>/edit/<?php echo $site_id; ?>";
										});
									},
									edit: function(){
										$.post(
											'<?php echo site_url(); echo $controller; ?>/ajax_fetch_cvs_standard/'+this.id+'/cvs',
											{'id': this.id,'type':'cvs'}
										).done(function(data){
											d = JSON.parse(data);

											$('select[name="cylinder_id"]').val(d.coa_id).trigger("chosen:updated");
											$('input[name="date_on"]').val(d.date_on);
											$('input[name="date_off"]').val(d.date_off);
											$('input[name="dilution_factor"]').val(d.value);
										});
									},
									delete: function(cid){
										if(confirm("Are you sure you want to delete this record?")){
											jQuery.ajax({
												url: "<?php echo site_url(); echo $controller ?>/ajax_delete_cvs_standard/"+cid,
												type: "POST",
												data: {id: cid},
												dataType: "script",
												success: function(){
													jQuery("#cvs-"+cid).fadeOut(200);
													self.location = "<?php echo site_url(); echo $controller ?>/edit/<?php echo $site_id ?>";
													tab = "cvs-compounds";
												}
											});
										}
									}
								};
							</script>

							<div class="row">
								<div class="container-fluid">
									<div class="panel panel-default">
									<div class="panel-heading" style="height: 50px;"><button id="add-cvs" class="btn btn-default pull-right" data-option="0" data-toggle="modal" data-target="#add-cvs-modal">Add</button></div>
									<?php $cvs_total = count($cvs); ?>
										<div class="panel-body">
											<div class="col-sm-12 table-responsive">
												<table class="table table-striped table-bordered cylinder-table" id="cvs-table">
													<thead>
														<tr>
															<th>CYLINDER</th>
															<th>DATE ON</th>
															<th>DATE OFF</th>
															<th>BLEND RATIO</th>
															<th>Options</th>
														</tr>
													</thead>
													<tbody>
														<?php
														
														for($i=0; $i<$cvs_total; $i++){
															?>
														<tr id="cvs-<?php echo $cvs[$i]['id']; ?>">
															<td><a href="<?php echo site_url('/coa/edit') . '/' . $cvs[$i]['coa_id'];?>"><?php echo $cvs[$i]['cylinder']; ?></a></td>
															<td><?php echo $cvs[$i]['date_on']; ?></td>
															<td><?php echo ($cvs[$i]['date_off'] != NULL) ? $cvs[$i]['date_off'] : 'In Use'; ?></td>
															<td><?php echo $cvs[$i]['value']; ?></td>
															<td>
																<a href="javascript:;" data-option="<?php echo htmlentitiesX($cvs[$i]['id']); ?>" data-target="#add-cvs-modal" data-toggle="modal"><i class="fa fa-pencil-square-o"></i></a>
																<a style='color: red; cursor:pointer;' href="javascript:;" onclick='cvs.delete("<?php echo htmlentitiesX($cvs[$i]['id']); ?>"); ' ><i class="fa fa-trash-o"></i></a>
															</td>
														</tr>
															<?php
														}
														?>
													</tbody>
												</table>
											</div><!-- /.col-sm-12.table-responsive -->
										</div><!-- /.panel-body -->
									</div><!-- /.panel.panel-default -->
								</div><!-- /.cotainer-fluid -->
							</div>

							<div class="modal fade" id="add-cvs-modal">
								<div class="modal-dialog dialog-sm">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">Add cvs Cylinder</h4>
										</div>
										<div class="modal-body">
											<form class="form-horizontal" role="form" id="cvs-cylinder-form">
												<input type="hidden" name="cvs_site_id" value="<?php echo $site_id; ?>"/>
												<input type="hidden" name="standard_type" value="CVS"/>
												<div class="form-group">
													<label class="col-sm-3 control-label">Cylinder</label>
													<div class="col-sm-7">
														<select class="chosen-select" name="cylinder_id" data-placeholder="Choose Cylinder">
															<option></option>
															<?php 
															$tc = count($cylinder);
															for($i=0; $i<$tc; $i++){
																echo '<option value="'.$cylinder[$i]['id'].'">'.$cylinder[$i]['cylinder'].'</option>';
															}
															?>
														</select>
														<!--input class="form-control form-control-flat input-sm" type="text" name="cylinder" placeholder="Cylinder"-->
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Date On</label>
													<div class="col-sm-7">
														<input class="form-control form-control-flat input-sm datetimepicker" type="text" name="date_on" placeholder="yyyy-mm-dd hh:mm">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Date Off</label>
													<div class="col-sm-7">
														<input class="form-control form-control-flat input-sm datetimepicker" type="text" name="date_off" placeholder="yyyy-mm-dd hh:mm">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Blend Ratio</label>
													<div class="col-sm-7">
														<input 
															class="form-control form-control-flat input-sm inputmask" type="text" name="dilution_factor" 
															data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digitsOptional': false, 'placeholder': '0'" 
														 	style="text-align: right;" placeholder="0.00">
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary" id="save-cvs">Save changes</button>
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /#add-cvs -->