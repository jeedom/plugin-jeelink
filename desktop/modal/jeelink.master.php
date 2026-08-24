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
<div id='div_jeelinkMasterAlert' style="display:none;"></div>
<div class="row row-overflow">
	<div class="col-lg-2 col-md-3">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-default jeelinkMasterAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fas fa-plus-circle"></i> {{Ajouter un Jeedom cible}}</a>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%" /></li>
				<?php
				foreach ($masters as $master) {
					echo '<li class="cursor li_jeelinkMaster" data-jeelinkMaster_id="' . $master->getId() . '"><a>' . $master->getName() . '</a></li>';
				}
				?>
			</ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 jeelinkMaster" style="display:none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-success jeelinkMasterAction eqLogicAction roundedLeft" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-danger jeelinkMasterAction btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#jeelinkMasterConfigtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Configuration}}</a></li>
			<li role="presentation"><a href="#jeelinkMasterAffecttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Affectation}}</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="jeelinkMasterConfigtab">
				<br>
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
						<div class="form-group">
							<label class="col-md-2 control-label">{{Nom du Jeedom cible}}</label>
							<div class="col-md-5">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="id" style="display:none;">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="name" placeholder="{{Nom du Jeedom cible}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">{{Adresse}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Indiquer l'adresse du Jeedom cible}}"></i></sup>
							</label>
							<div class="col-md-5">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="address" placeholder="{{Adresse du Jeedom cible}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">{{Clé API Jeedom Link}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Indiquer la clé API Jeedom Link du Jeedom cible}}"></i></sup>
							</label>
							<div class="col-md-5">
								<input type="text" class="jeeLinkMasterAttr form-control" data-l1key="apikey" placeholder="{{Clé API Jeedom Link du Jeedom cible}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">{{Mode d'accès principal}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner le type d'accès : interne (réseau local) ou externe (accès distant)}}"></i></sup>
							</label>
							<div class="col-md-5">
								<select class="jeeLinkMasterAttr form-control" data-l1key="configuration" data-l2key="network::access_main">
									<option value="internal">{{Interne}}</option>
									<option value="external">{{Externe}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">{{Mode d'accès secondaire}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner le type d'accès : interne (réseau local) ou externe (accès distant)}}"></i></sup>
							</label>
							<div class="col-md-5">
								<select class="jeeLinkMasterAttr form-control" data-l1key="configuration" data-l2key="network::access">
									<option value="internal">{{Interne}}</option>
									<option value="external">{{Externe}}</option>
								</select>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="jeelinkMasterAffecttab">
				<a class="btn btn-default btn-sm pull-right" id="bt_jeelinkMasterAddEqLogic" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter un équipement}}</a>
				<br>
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-check-double"></i> {{Equipements sélectionnés}}</legend>
						<div id="div_jeelinkMasterEqLogicList"></div>
					</fieldset>
				</form>
			</div>
			<hr>
		</div>
	</div>
</div>

<script>
	$('.jeelinkMasterAction[data-action=add]').on('click', function() {
		$('.jeelinkMaster').show();
		$('.jeeLinkMasterAttr').value('');
	});

	$('#bt_jeelinkMasterAddEqLogic').on('click', function() {
		addJeelinkMasterEqLogic();
	});

	function addJeelinkMasterEqLogic(_eqLogic) {
		if (!isset(_eqLogic)) {
			_eqLogic = {};
		}
		var div = '<div class="jeelinkMasterEqLogic">';
		div += '<div class="form-group">';
		div += '<label class="col-md-2 control-label">{{Nom de l\'équipement}}</label>';
		div += '<div class="col-md-5 has-success">';
		div += '<div class="input-group">';
		div += '<span class="input-group-btn">';
		div += '<a class="btn btn-default bt_removeJeelinkMasterEqLogic roundedLeft"><i class="fas fa-minus-circle"></i></a>';
		div += '</span>';
		div += '<input class="jeelinkMasterEqLogicAttr form-control" data-l1key="eqLogic">';
		div += '<span class="input-group-btn">';
		div += '<a class="btn btn-default listEqLogic roundedRight"><i class="fas fa-list-alt"></i></a>';
		div += '</span>';
		div += '</div>';
		div += '</div>';
		div += '</div>';
		$('#div_jeelinkMasterEqLogicList').append(div);
		$('#div_jeelinkMasterEqLogicList .jeelinkMasterEqLogic:last').setValues(_eqLogic, '.jeelinkMasterEqLogicAttr');
	}

	$('#div_jeelinkMasterEqLogicList').on('click', '.listEqLogic', function() {
		var el = $(this);
		jeedom.eqLogic.getSelectModal({
			cmd: {
				type: 'info'
			}
		}, function(result) {
			el.closest('.jeelinkMasterEqLogic').find('.jeelinkMasterEqLogicAttr[data-l1key=eqLogic]').value(result.human);
		});
	});

	$('#div_jeelinkMasterEqLogicList').on('click', '.bt_removeJeelinkMasterEqLogic', function() {
		$(this).closest('.jeelinkMasterEqLogic').remove();
	});

	function displayJeelinkMaster(_id) {
		$('.li_jeelinkMaster').removeClass('active');
		$('.li_jeelinkMaster[data-jeelinkMaster_id=' + _id + ']').addClass('active');
		$.ajax({
			type: "POST",
			url: "plugins/jeelink/core/ajax/jeelink.ajax.php",
			data: {
				action: "get_jeelinkMaster",
				id: _id,
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error, $('#div_jeelinkMasterAlert'));
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_jeelinkMasterAlert').showAlert({
						message: data.result,
						level: 'danger'
					});
					return;
				}
				$('.jeelinkMaster').show();
				$('#div_jeelinkMasterEqLogicList').empty();
				$('.jeeLinkMasterAttr').value('');
				$('.jeelinkMaster').setValues(data.result, '.jeeLinkMasterAttr');
				if (!isset(data.result.configuration)) {
					data.result.configuration = {};
				}
				if (isset(data.result.configuration.eqLogics)) {
					for (var i in data.result.configuration.eqLogics) {
						addJeelinkMasterEqLogic(data.result.configuration.eqLogics[i]);
					}
				}
			}
		});
	}

	$('.li_jeelinkMaster').on('click', function() {
		displayJeelinkMaster($(this).attr('data-jeelinkMaster_id'));
	});

	$('.jeelinkMasterAction[data-action=save]').on('click', function() {
		var jeelink_master = $('.jeelinkMaster').getValues('.jeeLinkMasterAttr')[0];
		if (!isset(jeelink_master.configuration)) {
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
			error: function(request, status, error) {
				handleAjaxError(request, status, error, $('#div_jeelinkMasterAlert'));
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_jeelinkMasterAlert').showAlert({
						message: data.result,
						level: 'danger'
					});
					return;
				}
				$('#div_jeelinkMasterAlert').showAlert({
					message: '{{Sauvegarde réussie}}',
					level: 'success'
				});
				displayJeelinkMaster(data.result.id);
			}
		});
	});

	$('.jeelinkMasterAction[data-action=remove]').on('click', function() {
		bootbox.confirm('{{Etes-vous sûr de vouloir supprimer ce Jeedom cible}} ?', function(result) {
			if (result) {
				$.ajax({
					type: "POST",
					url: "plugins/jeelink/core/ajax/jeelink.ajax.php",
					data: {
						action: "remove_jeelinkMaster",
						id: $('.li_jeelinkMaster.active').attr('data-jeelinkMaster_id'),
					},
					dataType: 'json',
					error: function(request, status, error) {
						handleAjaxError(request, status, error, $('#div_jeelinkMasterAlert'));
					},
					success: function(data) {
						if (data.state != 'ok') {
							$('#div_jeelinkMasterAlert').showAlert({
								message: data.result,
								level: 'danger'
							});
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
