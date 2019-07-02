function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function () {
	//Form Submit for IE Browser
	$('button[type=\'submit\']').on('click', function () {
		$('form[id*=\'form-\']').submit();
	});

	// Highlight any found errors
	$('.invalid-tooltip').each(function () {
		var element = $(this).parent().find(':input');

		if (element.hasClass('form-control')) {
			element.addClass('is-invalid');
		}
	});

	$('.invalid-tooltip').show();

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({
		container: 'body',
		html: true
	});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function () {
		$('[data-toggle=\'tooltip\']').tooltip({
			container: 'body'
		});
	});

	// tooltip remove
	$('[data-toggle=\'tooltip\']').on('remove', function () {
		$(this).tooltip('dispose');
	});

	// Tooltip remove fixed
	$(document).on('click', '[data-toggle=\'tooltip\']', function (e) {
		$('body > .tooltip').remove();
	});

	// https://github.com/opencart/opencart/issues/2595
	$.event.special.remove = {
		remove: function (o) {
			if (o.handler) {
				o.handler.apply(this, arguments);
			}
		}
	}

	// Fix for overflow in responsive tables
	$('.table-responsive').on('show.bs.dropdown', function () {
		$('.table-responsive').css('overflow', 'inherit');
	});

	$('.table-responsive').on('hide.bs.dropdown', function () {
		$('.table-responsive').css('overflow', 'auto');
	});

	$('#button-menu').on('click', function (e) {
		e.preventDefault();

		$('#column-left').toggleClass('active');
	});

	// Set last page opened on the menu
	$('#menu a[href]').on('click', function () {
		sessionStorage.setItem('menu', $(this).attr('href'));
	});

	if (!sessionStorage.getItem('menu')) {
		$('#menu #dashboard').addClass('active');
	} else {
		// Sets active and open to selected page in the left column menu.
		$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').addClass('active');
	}

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').find('> a').addClass('active');

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('menu-open');

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').addClass('active');
});

// Image Manager
$(document).on('click', 'a[data-toggle=\'image\']', function (e) {
	var $element = $(this);
	var $popover = $element.data('bs.popover'); // element has bs popover?

	e.preventDefault();

	// destroy all image popovers
	$('a[data-toggle="image"]').popover('dispose');

	// remove flickering (do not re-add popover when clicking for removal)
	if ($popover) {
		return;
	}

	$element.popover({
		html: true,
		placement: 'right',
		trigger: 'manual',
		content: function () {
			return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil-alt"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>';
		}
	});

	$element.popover('show');

	$('#button-image').on('click', function () {
		var $button = $(this);
		var $icon = $button.find('> i');

		$('#modal-image').remove();

		$.ajax({
			url: 'index.php?url=common/filemanager&user_token=' + getURLVar('user_token') + '&target=' + $element.parents('td').find('input').attr('id') + '&thumb=' + $element.attr('id'),
			//url: 'index.php?url=common/filemanager&user_token=' + getURLVar('user_token') + '&target=' + encodeURIComponent($(this).attr('data-target')) + '&thumb=' + encodeURIComponent($(this).attr('data-thumb')),
			dataType: 'html',
			beforeSend: function () {
				$button.prop('disabled', true);
				if ($icon.length) {
					$icon.attr('class', 'fa fa-circle-o-notch fa-spin');
				}
			},
			complete: function () {
				$button.prop('disabled', false);

				if ($icon.length) {
					$icon.attr('class', 'fa fa-pencil');
				}
			},
			success: function (html) {
				$('body').append(html);

				$('#modal-image').modal('show');
			}
		});

		$element.popover('dispose');
	});

	$('#button-clear').on('click', function () {
		$element.find('img').attr('src', $element.find('img').attr('data-placeholder'));

		$element.parent().find('input').attr('value', '');

		$element.popover('dispose');
	});
});

// Event Source - Enquiries
var clicked = localStorage.getItem('clicked') == 'true';

$(document).on('click', '#enquiries a.nav-link', function () {
	// var $this = $(this);

	// if (!$this.data('clicked')) {
	// 	if (!clicked) {
	// 		clicked = true;
	// 	}
	// } else {
	// 	clicked = true;
	// }

	// $this.data('clicked', true);
	$('#enquiries .nav-link i').replaceWith('<i class="far fa-comments"></i>');
	$('#enquiries .nav-link span.badge').text('');

	clicked = true;
	localStorage.setItem('clicked', clicked);
});

if (typeof EventSource != 'undefined') {
	var source = new EventSource('index.php?url=common/notification/sseEnquiries&user_token=' + getURLVar(
		'user_token'));

	source.onmessage = function (event) {
		var json = JSON.parse(event.data);
		var data = json.data;

		if (data) {
			$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');

			html = '<a href="javascript:void();" class="dropdown-item enquiry">';
			if (data.status == 'unread') {
				html += '   <i class="fa fa-envelope mr-2"></i> <b>' + data.name + '</b>';
				html += '   <span class="float-right text-muted text-sm"><b>' + data.date_added + '</b></span>';
			} else {
				html += ' <i class="fa fa-envelope mr-2"></i> ' + data.name;
				html += ' <span class="float-right text-muted text-sm">' + data.date_added + '</span>';
			}
			html += '</a>';
			if (data.length > 1) {
				html += '<div class="dropdown-divider"></div>';
			}

			if (json.limit >= json.total) {
				if ($('#enquiries > .dropdown-menu .enquiry').length > json.limit) {
					$('#enquiries > .dropdown-menu .enquiry:last').fadeOut(300, function () {
						$(this).remove();
					});
				}

				$('#enquiries > .dropdown-menu > .dropdown-divider:first').after(html);
			} else {
				$('#enquiries > .dropdown-menu .enquiry:last').fadeOut(300, function () {
					$(this).remove();
				});

				$('#enquiries > .dropdown-menu > .dropdown-divider:first').after(html);
			}

			$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');
			$('#enquiries .nav-link span.badge').text(json.count);

			clicked = false;
			localStorage.setItem('clicked', clicked);
		}

		if (!clicked) {
			$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');
			$('#enquiries .nav-link span.badge').text(json.count);
		}

		if (json.count > 0 && $('#enquiries .nav-link span.badge').text()) {
			$('#enquiries .nav-link span.badge').text(json.count);
		} else {
			if (data) {
				$('#enquiries .nav-link span.badge').text(json.count);
			}

			$('#enquiries .nav-link span.badge').text('');
		}
	}
} else {
	//alert('YOUR BROWSER DOESN"T SUPPORT EVENT SOURCE RUNNING AJAX');
	setInterval(function () {
		$.ajax({
			url: 'index.php?url=common/notification/ajaxEnquiries&user_token=' + getURLVar('user_token'),
			type: 'GET',
			dataType: 'json',
			success: function (event) {
				var json = event;
				var data = json.data;

				if (data) {
					$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');

					html = '<a href="javascript:void();" class="dropdown-item enquiry">';
					if (data.status == 'unread') {
						html += '   <i class="fa fa-envelope mr-2"></i> <b>' + data.name + '</b>';
						html += '   <span class="float-right text-muted text-sm"><b>' + data.date_added + '</b></span>';
					} else {
						html += ' <i class="fa fa-envelope mr-2"></i> ' + data.name;
						html += ' <span class="float-right text-muted text-sm">' + data.date_added + '</span>';
					}
					html += '</a>';
					if (data.length > 1) {
						html += '<div class="dropdown-divider"></div>';
					}

					if (data.limit >= data.total) {
						if ($('#enquiries > .dropdown-menu .enquiry').length > data.limit) {
							$('#enquiries > .dropdown-menu .enquiry:last').fadeOut(300, function () {
								$(this).remove();
							});
						}

						$('#enquiries > .dropdown-menu > .dropdown-divider:first').after(html);
					} else {
						$('#enquiries > .dropdown-menu .enquiry:last').fadeOut(300, function () {
							$(this).remove();
						});

						$('#enquiries > .dropdown-menu > .dropdown-divider:first').after(html);
					}

					$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');
					$('#enquiries .nav-link span.badge').text(json.count);

					clicked = false;
					localStorage.setItem('clicked', clicked);
				}

				if (!clicked) {
					$('#enquiries .nav-link i').replaceWith('<i class="fas fa-comments faa-tada animated"></i>');
					$('#enquiries .nav-link span.badge').text(json.count);
				}

				if (json.count > 0 && $('#enquiries .nav-link span.badge').text()) {
					$('#enquiries .nav-link span.badge').text(json.count);
				} else {
					if (data) {
						$('#enquiries .nav-link span.badge').text(json.count);
					}

					$('#enquiries .nav-link span.badge').text('');
				}
			}
		});
	}, 3000);
}

// Event Source - Testimonials
var tclicked = localStorage.getItem('tclicked') == 'true';

$(document).on('click', '#testimonials a.nav-link', function () {
	$('#testimonials .nav-link i').replaceWith('<i class="far fa-bell"></i>');
	$('#testimonials .nav-link span.badge').text('');

	tclicked = true;
	localStorage.setItem('tclicked', tclicked);
});

if (typeof EventSource != 'undefined') {
	var source = new EventSource('index.php?url=common/notification/sseTestimonials&user_token=' + getURLVar(
		'user_token'));

	source.onmessage = function (event) {
		var json = JSON.parse(event.data);
		var data = json.data;

		if (data) {
			//for (var i = 0; i < json.data.length; i++) {
			$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');

			html = '<a href="#" class="dropdown-item testimonial">';
			html += ' <!-- Message Start --> ';
			html += ' <div class="media">';
			html +=
				' <img src="../image/profile.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">';
			html += ' <div class="media-body">';
			html += ' <h3 class="dropdown-item-title">';
			html += ' ' + data.name;
			if (data.status == 0) {
				html +=
					' <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>';
			}
			html += ' </h3>';
			html += ' <p class="text-sm">' + data.message + '</p>';
			html +=
				' <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> ' + data.date_added + ' Ago</p>';
			html += ' </div>';
			html += ' </div>';
			html += '</a>';
			if (data.length > 1) {
				html += '<div class="dropdown-divider"></div>';
			}

			if (data.limit >= data.total) {
				if ($('#testimonials > .dropdown-menu .testimonial').length > data.limit) {
					$('#testimonials > .dropdown-menu .testimonial:last').fadeOut(300, function () {
						$(this).remove();
					});
				}

				$('#testimonials > .dropdown-menu').prepend(html);
			} else {
				$('#testimonials > .dropdown-menu .testimonial:last').fadeOut(300, function () {
					$(this).remove();
				});

				$('#testimonials > .dropdown-menu').prepend(html);
			}

			$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');
			$('#testimonials .nav-link span.badge').text(json.count);

			tclicked = false;
			localStorage.setItem('tclicked', tclicked);
		}

		if (!tclicked) {
			$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');
			$('#testimonials .nav-link span.badge').text(json.count);
		}

		if (json.count > 0 && $('#testimonials .nav-link span.badge').text()) {
			$('#testimonials .nav-link span.badge').text(json.count);
		} else {
			if (data) {
				$('#testimonials .nav-link span.badge').text(json.count);
			}

			$('#testimonials .nav-link span.badge').text('');
		}
	}
} else {
	//alert('YOUR BROWSER DOESN"T SUPPORT EVENT SOURCE RUNNING AJAX');
	setInterval(function () {
		$.ajax({
			url: 'index.php?url=common/notification/ajaxTestimonials&user_token=' + getURLVar('user_token'),
			type: 'GET',
			dataType: 'json',
			success: function (event) {
				var json = event;
				var data = json.data;

				if (data) {
					//for (var i = 0; i < json.data.length; i++) {
					$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');

					html = '<a href="#" class="dropdown-item testimonial">';
					html += ' <!-- Message Start --> ';
					html += ' <div class="media">';
					html +=
						' <img src="../image/profile.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">';
					html += ' <div class="media-body">';
					html += ' <h3 class="dropdown-item-title">';
					html += ' ' + data.name;
					if (data.status == 0) {
						html +=
							' <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>';
					}
					html += ' </h3>';
					html += ' <p class="text-sm">' + data.message + '</p>';
					html +=
						' <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> ' + data.date_added + ' Ago</p>';
					html += ' </div>';
					html += ' </div>';
					html += '</a>';
					if (data.length > 1) {
						html += '<div class="dropdown-divider"></div>';
					}

					if (data.limit >= data.total) {
						if ($('#testimonials > .dropdown-menu .testimonial').length > data.limit) {
							$('#testimonials > .dropdown-menu .testimonial:last').fadeOut(300, function () {
								$(this).remove();
							});
						}

						$('#testimonials > .dropdown-menu').prepend(html);
					} else {
						$('#testimonials > .dropdown-menu .testimonial:last').fadeOut(300, function () {
							$(this).remove();
						});

						$('#testimonials > .dropdown-menu').prepend(html);
					}

					$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');
					$('#testimonials .nav-link span.badge').text(json.count);

					tclicked = false;
					localStorage.setItem('tclicked', tclicked);
				}

				if (!tclicked) {
					$('#testimonials .nav-link i').replaceWith('<i class="fas fa-bell faa-ring animated"></i>');
					$('#testimonials .nav-link span.badge').text(json.count);
				}

				if (json.count > 0 && $('#testimonials .nav-link span.badge').text()) {
					$('#testimonials .nav-link span.badge').text(json.count);
				} else {
					if (data) {
						$('#testimonials .nav-link span.badge').text(json.count);
					}

					$('#testimonials .nav-link span.badge').text('');
				}
			}
		});
	}, 3000);
}

// if (window.performance) {
// 	console.info("window.performance works fine on this browser");
// }

// if (performance.navigation.type == 1) {
// 	console.info("This page is reloaded");
// } else {
// 	console.info("This page is not reloaded");
// }

// Image Manager
/*$(document).on('click', '[data-toggle=\'image\']', function (e) {
	var element = this;

	$('#modal-image').remove();

	$.ajax({
		url: 'index.php?url=common/filemanager&user_token=' + getURLVar('user_token') + '&target=' + encodeURIComponent($(this).attr('data-target')) + '&thumb=' + encodeURIComponent($(this).attr('data-thumb')),
		dataType: 'html',
		beforeSend: function () {
			$(element).button('loading');
		},
		complete: function () {
			$(element).button('reset');
		},
		success: function (html) {
			$('body').append(html);

			$('#modal-image').modal('show');
		}
	});
});

$(document).on('click', '[data-toggle=\'clear\']', function () {
	$($(this).attr('data-thumb')).attr('src', $($(this).attr('data-thumb')).attr('data-placeholder'));

	$($(this).attr('data-target')).val('');
});*/

// Chain ajax calls.
class Chain {
	constructor() {
		this.start = false;
		this.data = [];
	}

	attach(call) {
		this.data.push(call);

		if (!this.start) {
			this.execute();
		}
	}

	execute() {
		if (this.data.length) {
			this.start = true;

			(this.data.shift())().done(function () {
				chain.execute();
			});
		} else {
			this.start = false;
		}
	}
}

var chain = new Chain();

// Autocomplete
(function ($) {
	$.fn.autocomplete = function (option) {
		return this.each(function () {
			var $this = $(this);
			var $dropdown = $('<div class="dropdown-menu"/>');

			this.timer = null;
			this.items = [];

			$.extend(this, option);

			if (!$(this).parent().hasClass('input-group')) {
				$(this).wrap('<div class="dropdown">');
			} else {
				$(this).parent().wrap('<div class="dropdown">');
			}

			$this.attr('autocomplete', 'off');
			$this.active = false;

			// Focus
			$this.on('focus', function () {
				this.request();
			});

			// Blur
			$this.on('blur', function (e) {
				if (!$this.active) {
					this.hide();
				}
			});

			$this.parent().on('mouseover', function (e) {
				$this.active = true;
			});

			$this.parent().on('mouseout', function (e) {
				$this.active = false;
			});

			// Keydown
			$this.on('keydown', function (event) {
				switch (event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function (event) {
				event.preventDefault();

				var value = $(event.target).attr('href');

				if (value && this.items[value]) {
					this.select(this.items[value]);

					this.hide();
				}
			}

			// Show
			this.show = function () {
				$dropdown.addClass('show');
			}

			// Hide
			this.hide = function () {
				$dropdown.removeClass('show');
			}

			// Request
			this.request = function () {
				clearTimeout(this.timer);

				this.timer = setTimeout(function (object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 50, this);
			}

			// Response
			this.response = function (json) {
				var html = '';
				var category = {};
				var name;
				var i = 0,
					j = 0;

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						// update element items
						this.items[json[i]['value']] = json[i];

						if (!json[i]['category']) {
							// ungrouped items
							html += '<a href="' + json[i]['value'] + '" class="dropdown-item">' + json[i]['label'] + '</a>';
						} else {
							// grouped items
							name = json[i]['category'];

							if (!category[name]) {
								category[name] = [];
							}

							category[name].push(json[i]);
						}
					}

					for (name in category) {
						html += '<h6 class="dropdown-header">' + name + '</h6>';

						for (j = 0; j < category[name].length; j++) {
							html += '<a href="' + category[name][j]['value'] + '" class="dropdown-item">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$dropdown.html(html);
			}

			$dropdown.on('click', '> a', $.proxy(this.click, this));

			$this.after($dropdown);
		});
	}
})(window.jQuery);

+
function ($) {
	'use strict';

	// BUTTON PUBLIC CLASS DEFINITION
	// ==============================

	var Button = function (element, options) {
		this.$element = $(element)
		this.options = $.extend({}, Button.DEFAULTS, options)
		this.isLoading = false
	}

	Button.VERSION = '3.3.5'

	Button.DEFAULTS = {
		loadingText: 'loading...'
	}

	Button.prototype.setState = function (state) {
		var d = 'disabled'
		var $el = this.$element
		var val = $el.is('input') ? 'val' : 'html'
		var data = $el.data()

		state += 'Text'

		if (data.resetText == null) $el.data('resetText', $el[val]())

		// push to event loop to allow forms to submit
		setTimeout($.proxy(function () {
			$el[val](data[state] == null ? this.options[state] : data[state])

			if (state == 'loadingText') {
				this.isLoading = true
				$el.addClass(d).attr(d, d)
			} else if (this.isLoading) {
				this.isLoading = false
				$el.removeClass(d).removeAttr(d)
			}
		}, this), 0)
	}

	Button.prototype.toggle = function () {
		var changed = true
		var $parent = this.$element.closest('[data-toggle="buttons"]')

		if ($parent.length) {
			var $input = this.$element.find('input')
			if ($input.prop('type') == 'radio') {
				if ($input.prop('checked')) changed = false
				$parent.find('.active').removeClass('active')
				this.$element.addClass('active')
			} else if ($input.prop('type') == 'checkbox') {
				if (($input.prop('checked')) !== this.$element.hasClass('active')) changed = false
				this.$element.toggleClass('active')
			}
			$input.prop('checked', this.$element.hasClass('active'))
			if (changed) $input.trigger('change')
		} else {
			this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
			this.$element.toggleClass('active')
		}
	}


	// BUTTON PLUGIN DEFINITION
	// ========================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data = $this.data('bs.button')
			var options = typeof option == 'object' && option

			if (!data) $this.data('bs.button', (data = new Button(this, options)))

			if (option == 'toggle') data.toggle()
			else if (option) data.setState(option)
		})
	}

	var old = $.fn.button

	$.fn.button = Plugin
	$.fn.button.Constructor = Button


	// BUTTON NO CONFLICT
	// ==================

	$.fn.button.noConflict = function () {
		$.fn.button = old
		return this
	}


	// BUTTON DATA-API
	// ===============

	$(document)
		.on('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
			var $btn = $(e.target)
			if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
			Plugin.call($btn, 'toggle')
			if (!($(e.target).is('input[type="radio"]') || $(e.target).is('input[type="checkbox"]'))) e.preventDefault()
		})
		.on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function (e) {
			$(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type))
		})

}(jQuery);