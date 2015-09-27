							<script>
							$(function(){

								$('#add-rts-modal').on('show.bs.modal', function(event){
									var button = $(event.relatedTarget);
									rts.id = button.data('option');

									if(rts.id>0){
										rts.edit(rts.id);
									}
								});
								$('#save-rts').on("click", function(){
									rts.save();
								});
							});

								var rts = {
									id: 0,
									site_id: 0,
									site_name: "",
									save: function(){
										var formdata = $("#rts-cylinder-form").serialize();
										var target = '';
										
										if(this.id>0) target = 'update_rts_standard/'+this.id;
										else target = 'add_rts_standard/'+this.id;
										
										$.post(
											'<?php echo site_url(); echo $controller; ?>/ajax_'+target,
											{ 'data': formdata }
										).done(function(){
											self.location = "<?php echo site_url(); echo $controller; ?>/edit/<?php echo $site_id; ?>";
										});
									},
									edit: function(){
										$.post(
											'<?php echo site_url(); echo $controller; ?>/ajax_fetch_rts_standard/'+this.id+'/rts',
											{'id': this.id,'type':'rts'}
										).done(function(data){
											d = JSON.parse(data);

											$('select[name="cylinder_id"]').val(d.coa_id);
											$('input[name="date_on"]').val(d.date_on);
											$('input[name="date_off"]').val(d.date_off);
											$('input[name="dilution_factor"]').val(d.value);
										});
									},
									delete: function(cid){
										if(confirm("Are you sure you want to delete this record?")){
											jQuery.ajax({
												url: "<?php echo site_url(); echo $controller ?>/ajax_delete_rts_standard/"+cid,
												type: "POST",
												data: {id: cid},
												dataType: "script",
												success: function(){
													jQuery("#rts-"+cid).fadeOut(200);
													self.location = "<?php echo site_url(); echo $controller ?>/edit/<?php echo $site_id ?>";
													tab = "rts-compounds";
												}
											});
										}
									}
								};
							</script>

							<div class="row">
								<div class="container-fluid">
									<div class="panel panel-default">
									<div class="panel-heading" style="height: 50px;"><button id="add-rts" class="btn btn-default pull-right" data-option="0" data-toggle="modal" data-target="#add-rts-modal">Add</button></div>
									<?php $rts_total = count($rts); ?>
										<div class="panel-body">
											<div class="col-sm-12 table-responsive">
												<table class="table table-striped table-bordered cylinder-table" id="rts-table">
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
														for($i=0; $i<$rts_total; $i++){
															?>
														<tr id="rts-<?php echo $rts[$i]['id']; ?>">
															<td><a href="#"><?php echo $rts[$i]['cylinder']; ?></a></td>
															<td><?php echo $rts[$i]['date_on']; ?></td>
															<td><?php echo ($rts[$i]['date_off'] != NULL) ? $rts[$i]['date_off'] : ''; ?></td>
															<td><?php echo $rts[$i]['value']; ?></td>
															<td>
																<a href="javascript:;" data-option="<?php echo htmlentitiesX($rts[$i]['id']); ?>" data-target="#add-rts-modal" data-toggle="modal"><i class="fa fa-pencil-square-o"></i></a>
																<a style='color: red; cursor:pointer;' href="javascript:;" onclick='rts.delete("<?php echo htmlentitiesX($rts[$i]['id']); ?>"); ' ><i class="fa fa-trash-o"></i></a>
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

							<div class="modal fade" id="add-rts-modal">
								<div class="modal-dialog dialog-sm">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">Add RTS Cylinder</h4>
										</div>
										<div class="modal-body">
											<form class="form-horizontal" role="form" id="rts-cylinder-form">
												<input type="hidden" name="rts_site_id" value="<?php echo $site_id; ?>"/>
												<input type="hidden" name="standard_type" value="RTS"/>
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
														<input class="form-control form-control-flat input-sm inputmask" type="text" name="dilution_factor" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true" style="text-align: right;" placeholder="0.00">
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary" id="save-rts">Save changes</button>
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /#add-rts -->