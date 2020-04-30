//FORM INSCRIPTION
$(document).ready(function() {
	$("#erreurMail").hide();
	$("#erreurTel").hide();
	$("#erreurCorrespondanceMDP").hide();
	$("#erreurSalle").hide();
	$("#erreurLigue").hide();
	$("#erreurReservation").hide();
	$("#erreurModifReservation").hide();

	/*=====================
	FORMULAIRE INSCRIPTION
	=====================*/
	$("#formInscr").submit(function(event) {

		//TEST REMPLISSAGE DES INPUTS
		$("#formInscr input").each(function() {
			if ($(this).val() == "") {
				$(this).css("border", "red 3px solid");
				event.preventDefault();
			} else {
				$(this).css("border", "grey 1px solid");
			}
		});

		//TEST VALIDITE MAIL
		let emailAdresse = $("input[type=email]").val();
		let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
		if (!emailRegex.test(emailAdresse)) {
			event.preventDefault();
			$("input[type=email]").css("border", "red 3px solid");
			$("#erreurMail").show().css("color", "red");
		} else {
			$("input[type=email]").css("border", "grey 1px solid");
			$("#erreurMail").hide();
		}

		//TEST VALIDITE TELEPHONE
		let numTel = $("input[type=tel]").val();
		let telRegex = /^(0[1-678])(?:[ _.-]?(\d{2})){4}$/;
		if (!telRegex.test(numTel)) {
			event.preventDefault();
			$("input[type=tel]").css("border", "red 3px solid");
			$("#erreurTel").show().css("color", "red");
		} else {
			$("input[type=tel]").css("border", "grey 1px solid");
			$("#erreurTel").hide();
		}

		if ($("input[name='tab[MotDePasse]']").val() != $("input[name='mdp_conf']").val()) {
			event.preventDefault();
			$("input[name='mdp_conf']").css("border", "red 3px solid");
			$("#erreurCorrespondanceMDP").show().css("color", "red");
		} else {
			$("input[name='mdp_conf']").css("border", "grey 1px solid");
			$("#erreurCorrespondanceMDP").hide();
		}
	});

	/*=====================
	FORMULAIRES ADMIN
	=====================*/

	/*######################
	AJOUTS SALLES ET LIGUES
	######################*/

	let postSalle = function() {
		$.post(
			'index.php?action=AjoutSalle',
			$("#formAjoutSalle").serialize(),

			function(data) {
				if (data == 'Success') {
					$("#ModalAjoutSalle").modal('toggle');
					$('#tab-content').load(location.href + " #tab-content", function() {
						$(".edit-button-salle").on('click', function() {
							recupererLigneSalle($(this));
						});
						$(".edit-button-ligues").on('click', function() {
							recupererLigneLigue($(this));
						});
					});
				} else {
					$("#erreurSalle").show().text(data);
				}
			},

			'text'
		);
	}

	let postLigue = function() {
		$.post(
			'index.php?action=AjoutLigue',
			$("#formAjoutLigue").serialize(),

			function(data) {
				if (data == 'Success') {
					$("#ModalAjoutLigue").modal('toggle');
					$('#gestionLigues').load(location.href + " #gestionLigues", function() {
						$(".edit-button-salle").on('click', function() {
							recupererLigneSalle($(this));
						});
						$(".edit-button-ligues").on('click', function() {
							recupererLigneLigue($(this));
						});
					});
				} else {
					$("#erreurLigue").show().text(data);
				}
			},

			'text'
		);
	}

	$("#formAjoutSalle").keypress(function(e) {
		if (e.which == 13) {
			e.preventDefault();
			postSalle();
		}
	})

	$("#saveAjoutSalle").click(function() {
		postSalle();
	});

	$("#formAjoutLigue").keypress(function(e) {
		if (e.which == 13) {
			e.preventDefault();
			postLigue();
		}
	});

	$("#saveAjoutLigue").click(function() {
		postLigue();
	});

	/*######################
	MODIFS SALLES
	######################*/

	let recupererLigneSalle = function(obj) {
		let tbValLigne = [];
		// Récupère éléments de la ligne sur laquelle on a cliqué
		obj.parent().siblings().each(function() {
			tbValLigne.push($(this).text());
		});
		$("option:selected").removeAttr("selected");
		// Remet valeurs et attributs sur les éléments d'input du formulaire
		$("#NomSalleModif").val(tbValLigne[0]);
		$("#CodeTypeSalle_FKModif option[id='" + tbValLigne[1] + "']").prop('selected', true);
		$("#CodeBatiment_FKModif option[id='" + tbValLigne[2] + "']").prop('selected', true);
		if (tbValLigne[3] == "Activée") {
			$("#Activée").prop('checked', true);
		} else {
			$("#Désactivée").prop('checked', true);
		}
		$("#NumSalleModif").val(tbValLigne[4]);
	}

	$(".edit-button-salle").click(function() {
		recupererLigneSalle($(this));
	});

	var modifSalle = function() {
		$.post(
			'index.php?action=ModifSalle',
			$("#formModifSalle").serialize(),

			function(data) {
				if (data == 'Success') {
					$("#ModalModifSalle").modal('toggle');
					$('#gestionSalles').load(location.href + " #gestionSalles", function() {
						$(".edit-button-salle").on('click', function() {
							recupererLigneSalle($(this));
						});
					});
				} else {
					$("#erreurModifSalle").show().text(data);
				}
			},

			'text'
		);
	}

	$("#formModifSalle").keypress(function(e) {
		if (e.which == 13) {
			e.preventDefault();
			modifSalle();
		}
	})

	$("#saveModifSalle").click(function() {
		modifSalle();
	});

	/*######################
	MODIFS LIGUES
	######################*/

	let recupererLigneLigue = function(obj) {
		let tbValLigneLigue = [];
		obj.parent().siblings().each(function() {
			tbValLigneLigue.push($(this).text());
		});
		$("option:selected").removeAttr("selected");
		$("#NomLigueModif").val(tbValLigneLigue[0]);
		$("#NumUser_FKModif option[id='" + tbValLigneLigue[1] + "']").prop('selected', true);
		if (tbValLigneLigue[2] == "Activée") {
			$("#LigueActivée").prop('checked', true);
		} else {
			$("#LigueDésactivée").prop('checked', true);
		}
		$("#NumLigueModif").val(tbValLigneLigue[3]);
	}

	$(".edit-button-ligues").click(function() {
		recupererLigneLigue($(this));
	});

	let modifLigue = function() {
		$.post(
			'index.php?action=ModifLigue',
			$("#formModifLigue").serialize(),

			function(data) {
				if (data == 'Success') {
					$("#ModalModifLigue").modal('toggle');
					$('#gestionLigues').load(location.href + " #gestionLigues", function() {
						$(".edit-button-ligues").on('click', function() {
							recupererLigneLigue($(this));
						});
					});
				} else {
					$("#erreurModifLigue").show().text(data);
				}
			},

			'text'
		);
	}

	$("#formModifLigue").keypress(function(e) {
		if (e.which == 13) {
			e.preventDefault();
			modifLigue();
		}
	})

	$("#saveModifLigue").click(function() {
		modifLigue();
	});


	/*######################
	AJOUT RESERVATION
	######################*/

	$("#saveReservation").click(function(e) {
		e.preventDefault();
		// Si dateFin non renseignée, ou dateFin = dateDébut, ou dateDébut avant jour actuel, ou dateFin < dateDébut -> afficher erreur
		if (($("#DateFinReserver").datetimepicker("date") == null) || ($("#DateFinReserver").datetimepicker("date").isSame($("#DateDebutReserver").datetimepicker("date"))) ||
			($("#DateDebutReserver").datetimepicker("date") < moment()) || ($("#DateFinReserver").datetimepicker("date").isBefore($("#DateDebutReserver").datetimepicker("date")))) {
			$("#erreurReservation").show();
		} else {
			$("#erreurReservation").hide();
			$.post(
				'index.php?action=reservation',
				$("#formReservation").serialize(),
				function(data) {
					if (data.NomSalle) {
						alert("Votre réservation a bien été prise en compte.\nLa salle " + data.NomSalle + " vous a été attribuée.");
						$('#calendarModalReservation').modal("toggle");
						calendar.refetchEvents();
					}
				},
				'json'
			).fail(function() {
				alert("Toutes les salles répondant aux critères souhaités sont réservées durant ce créneau.");
			});
		}
	});


	/*######################
	MODIF RESERVATION
	######################*/

	$("#saveModifReservation").click(function(e) {
		e.preventDefault();
		if (($("#ModifDateFinReserver").datetimepicker("date") == null) || ($("#ModifDateFinReserver").datetimepicker("date").isSame($("#ModifDateDebutReserver").datetimepicker("date"))) ||
			($("#ModifDateDebutReserver").datetimepicker("date") < moment()) || ($("#ModifDateFinReserver").datetimepicker("date").isBefore($("#ModifDateDebutReserver").datetimepicker("date")))) {
			$("#erreurModifReservation").show();
		} else {
			$("#erreurModifReservation").hide();
			$.post(
				'index.php?action=modifReservation',
				$("#formReservationModif").serialize(),
				function(data) {
					alert("La modification de votre réservation a bien été prise en compte.\nLa salle " + data.NomSalle + " vous a été attribuée.");
					$('#calendarModalModifReservation').modal("toggle");
					calendar.refetchEvents();
				},
				'json'
			).fail(function() {
				alert("Toutes les salles répondant aux critères souhaités sont réservées durant ce créneau.");
			});
		}
	});



	/*######################
	SUPPRESSION RESERVATION
	######################*/

	$("#deleteReservation").click(function(e) {
		e.preventDefault();
		$.post(
			'index.php?action=supprResa',
			$("#IDReservation"),
			function(data) {
				alert("La suppression a bien été effectuée.");
				$('#calendarModalModifReservation').modal("toggle");
				calendar.refetchEvents();
			}
		).fail(function() {
			alert("La suppression de la réservation n'a pas pu être effectuée.");
		});
	})

	/*######################
	FullCalendar
	######################*/

	function offsetHour(datestr, offset) {
		return moment(datestr).add(offset, 'hours');
	}

	let calendarEl = document.getElementById('calendar');

	let calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: ['dayGrid', 'timeGrid', 'bootstrap', 'interaction'],
		defaultView: 'timeGridDay',
		header: {
			left: 'today, ,timeGridWeek,timeGridDay',
			center: 'title',
			right: 'prev,next'
		},
		slotDuration: '01:00:00',
		timeZone: "local",
		locale: 'fr',
		allDaySlot: false,
		weekends: false,
		selectable: true,
		height: 'auto',
		minTime: "08:00",
		maxTime: "18:00",
		titleFormat: {
			month: 'long',
			year: 'numeric',
			day: 'numeric',
			weekday: 'long'
		},
		events: 'index.php?action=getEvents',
		eventColor: '#216583',
		eventTextColor: '#efefef',
		eventClick: function(info) {
			$("#erreurReservation").hide();
			$("#erreurModifReservation").hide();
			$('#calendarModalModifReservation').modal("toggle");
			$("option:selected").removeAttr("selected");
			$("#ModifReservation_NomsLigues option[value='" + info.event.extendedProps.NumLigue + "']").prop('selected', true);
			$("#ModifReservation_TypeSalle option[value='" + info.event.extendedProps.CodeTypeSalle + "']").prop('selected', true);
			$('#ModifDateDebutReserver').datetimepicker('date', info.event.start);
			$('#ModifDateFinReserver').datetimepicker('date', info.event.end);
			$("#IDReservation").val(info.event.id);
		},
		dateClick: function(event) {
			$("#erreurReservation").hide();
			$("#erreurModifReservation").hide();
			let now = moment().format('YYYY/MM/DD HH:mm');
			let dateStr = moment(event.dateStr).format("LL");
			let dateClicked = moment(event.dateStr).format('YYYY/MM/DD HH:mm');
			let dateTimeOffset = offsetHour(event.dateStr, 1);

			// Double formatage de la date (dateTimeStr) peu estétique, mais si on l'enlève, soit le if marche pas, soit la date n'est plus passée au modal

			if (dateClicked < now) {
				alert("Vous ne pouvez pas sélectionner une date antérieure à l'instant qui nous définit en la présente seconde");
			} else {
				$('#calendarModalReservation').modal("toggle");
				$('#modalTitleResa').html(dateStr);
				$('#DateDebutReserver').datetimepicker('date', moment(event.dateStr).format('DD/MM/YYYY HH:mm'));
				$('#DateFinReserver').datetimepicker('date', moment(dateTimeOffset, 'DD/MM/YYYY HH:mm'));
			};
		}
	});

	calendar.render();

	/*######################
	DateTimePicker
	######################*/

	$('#DateDebutReserver').datetimepicker({
		daysOfWeekDisabled: [0, 6],
		stepping: 60,
		enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
		icons: {
			time: 'far fa-clock',
		}
	});

	$('#DateFinReserver').datetimepicker({
		useCurrent: false,
		daysOfWeekDisabled: [0, 6],
		stepping: 60,
		enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
		icons: {
			time: 'far fa-clock',
		}
	});

	$('#ModifDateDebutReserver').datetimepicker({
		daysOfWeekDisabled: [0, 6],
		stepping: 60,
		enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
		icons: {
			time: 'far fa-clock',
		}
	});

	$('#ModifDateFinReserver').datetimepicker({
		useCurrent: false,
		daysOfWeekDisabled: [0, 6],
		stepping: 60,
		enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
		icons: {
			time: 'far fa-clock',
		}
	});

	//Bloque la selection de date de début à avant la fin, et la date de fin à après le début (on ne peut pas depsser ces pôles)
	// $("#DateDebutReserver").on("change.datetimepicker", function(e) {
	// 	$('#DateFinReserver').datetimepicker('minDate', e.date);
	// });
	//
	// $("#DateFinReserver").on("change.datetimepicker", function(e) {
	// 	$('#DateDebutReserver').datetimepicker('maxDate', e.date);
	// });

	// $("#ModifDateDebutReserver").on("change.datetimepicker", function(e) {
	// 	$('#ModifDateFinReserver').datetimepicker('minDate', e.date);
	// });
	//
	// $("#ModifDateFinReserver").on("change.datetimepicker", function(e) {
	// 	$('#ModifDateDebutReserver').datetimepicker('maxDate', e.date);
	// });
});