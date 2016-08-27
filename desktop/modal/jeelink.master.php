<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$masters = jeelink_master::all();
?>
<div id='div_jeelinkMasterAlert' style="display: none;"></div>
<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-default jeelinkMasterAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un jeedom distant}}</a>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
foreach ($masters as $master) {
	echo '<li class="cursor li_jeelinkMaster" data-jeelinkMaster_id="' . $master->getId() . '"><a>' . $master->getName() . '</a></li>';
}
?>
			</ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8 col-xs-8 jeelinkMaster" style="border-left: solid 1px #EEE; padding-left: 25px;display:none;">
		<a class="btn btn-success jeelinkMasterAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger jeelinkMasterAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#jeelinkMasterConfigtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Configuration}}</a></li>
			<li role="presentation"><a href="#jeelinkMasterAffecttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Affectation}}</a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="jeelinkMasterConfigtab">
				<form class="form-horizontal">
					<fieldset>
						<legend>{{Général}}</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Nom du jeedom distant}}</label>
							<div class="col-sm-3">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="name" placeholder="{{Nom du jeedom distant}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Addresse}}</label>
							<div class="col-sm-3">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="address" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Clef API}}</label>
							<div class="col-sm-3">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="apikey" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Mode d'accès}}</label>
							<div class="col-sm-3">
								<select class="jeeLinkMasterAttr form-control" data-l1key="configuration" data-l2key="network::access" >
									<option value="internal">{{Interne}}</option>
									<option value="external">{{Externe}}</option>
								</select>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="jeelinkMasterAffecttab">
				<a class="btn btn-success btn-sm pull-right" id="bt_jeelinkMasterAddEqLogic"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a><br>
				<form class="form-horizontal">
					<fieldset>
						<div id="div_jeelinkMasterEqLogicList"></div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<script>
	$('.jeelinkMasterAction[data-action=add]').on('click',function(){
		$('.jeelinkMaster').show();
		$('.jeeLinkMasterAttr').value('');
	});

	$('#bt_jeelinkMasterAddEqLogic').on('click',function(){
		addJeelinkMasterEqLogic();
	});

	function addJeelinkMasterEqLogic(_eqLogic){
		if (!isset(_eqLogic)) {
			_eqLogic = {};
		}
		var div = '<div class="jeelinkMasterEqLogic">';
		div += '<div class="form-group">';
		div += '<label class="col-sm-1 control-label">{{Equipement}}</label>';
		div += '<div class="col-sm-5 has-success">';
		div += '<div class="input-group">';
		div += '<span class="input-group-btn">';
		div += '<a class="btn btn-default bt_removeJeelinkMasterEqLogic btn-sm"><i class="fa fa-minus-circle"></i></a>';
		div += '</span>';
		div += '<input class="jeelinkMasterEqLogicAttr form-control input-sm" data-l1key="eqLogic" />';
		div += '<span class="input-group-btn">';
		div += '<a class="btn btn-sm listEqLogic btn-success"><i class="fa fa-list-alt"></i></a>';
		div += '</span>';
		div += '</div>';
		div += '</div>';
		div += '</div>';
		$('#div_jeelinkMasterEqLogicList').append(div);
		$('#div_jeelinkMasterEqLogicList .jeelinkMasterEqLogic:last').setValues(_eqLogic, '.jeelinkMasterEqLogicAttr');
	}

	$('#div_jeelinkMasterEqLogicList').on('click','.listEqLogic', function() {
		var el = $(this);
		jeedom.eqLogic.getSelectModal({cmd: {type: 'info'}}, function(result) {
			el.closest('.jeelinkMasterEqLogic').find('.jeelinkMasterEqLogicAttr[data-l1key=eqLogic]').value(result.human);
		});
	});

	$('#div_jeelinkMasterEqLogicList').on('click','.bt_removeJeelinkMasterEqLogic', function() {
		$(this).closest('.jeelinkMasterEqLogic').remove();
	});

	function displayJeelinkMaster(_id){
		$('.li_jeelinkMaster').removeClass('active');
		$('.li_jeelinkMaster[data-jeelinkMaster_id='+_id+']').addClass('active');
		$.ajax({
			type: "POST",
			url: "plugins/jeelink/core/ajax/jeelink.ajax.php",
			data: {
				action: "get_jeelinkMaster",
				id: _id,
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_jeelinkMasterAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_jeelinkMasterAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('.jeelinkMaster').show();
				$('#div_jeelinkMasterEqLogicList').empty();
				$('.jeeLinkMasterAttr').value('');
				$('.jeelinkMaster').setValues(data.result,'.jeeLinkMasterAttr');
				if(!isset(data.result.configuration)){
					data.result.configuration = {};
				}
				if(isset(data.result.configuration.eqLogics)){
					for(var i in data.result.configuration.eqLogics){
						addJeelinkMasterEqLogic(data.result.configuration.eqLogics[i]);
					}
				}
			}
		});
	}

	$('.li_jeelinkMaster').on('click',function(){
		displayJeelinkMaster($(this).attr('data-jeelinkMaster_id'));
	});

	$('.jeelinkMasterAction[data-action=save]').on('click',function(){
		var jeelink_master = $('.jeelinkMaster').getValues('.jeeLinkMasterAttr')[0];
		if(!isset(jeelink_master.configuration)){
			jeelink_master.configuration = {};
		}
		jeelink_master.configuration.eqLogics = $('#div_jeelinkMasterEqLogicList .jeelinkMasterEqLogic').getValues('.jeelinkMasterEqLogicAttr');
		$.ajax({
			type: "POST",
			url: "plugins/jeelink/core/ajax/jeelink.ajax.php",
			data: {
				action: "save_jeelinkMaster",
				jeelink_master: json_encode(jeelink_master),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_jeelinkMasterAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_jeelinkMasterAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_jeelinkMasterAlert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
				displayJeelinkMaster(data.result.id);
			}
		});
	});

	$('.jeelinkMasterAction[data-action=remove]').on('click',function(){
		bootbox.confirm('{{Etês-vous sûr de vouloir supprimer ce jeedom distant ?}}', function (result) {
			if (result) {
				$.ajax({
					type: "POST",
					url: "plugins/jeelink/core/ajax/jeelink.ajax.php",
					data: {
						action: "remove_jeelinkMaster",
						id: $('.li_jeelinkMaster.active').attr('data-jeelinkMaster_id'),
					},
					dataType: 'json',
					error: function (request, status, error) {
						handleAjaxError(request, status, error,$('#div_jeelinkMasterAlert'));
					},
					success: function (data) {
						if (data.state != 'ok') {
							$('#div_jeelinkMasterAlert').showAlert({message: data.result, level: 'danger'});
							return;
						}
						$('.li_jeelinkMaster.active').remove();
						$('.jeelinkMaster').hide();
					}
				});
			}
		});
	});
</script>