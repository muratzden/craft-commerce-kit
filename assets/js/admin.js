(function () {
	function copyText(text, button) {
		function markCopied() {
			var original = button.textContent;

			button.textContent = 'Copied';
			button.classList.add('is-copied');

			window.setTimeout(function () {
				button.textContent = original;
				button.classList.remove('is-copied');
			}, 1600);
		}

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(markCopied).catch(function () {
				fallbackCopy(text, markCopied);
			});
			return;
		}

		fallbackCopy(text, markCopied);
	}

	function fallbackCopy(text, callback) {
		var textarea = document.createElement('textarea');

		textarea.value = text;
		textarea.setAttribute('readonly', 'readonly');
		textarea.style.position = 'absolute';
		textarea.style.left = '-9999px';
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
		callback();
	}

	function escapeRegExp(value) {
		return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	function submitLayoutForm(form) {
		if (!form || form.__cckLayoutSubmitting) {
			return;
		}

		form.__cckLayoutSubmitting = true;

		window.setTimeout(function () {
			form.__cckLayoutSubmitting = false;

			if (form.requestSubmit) {
				form.requestSubmit();
				return;
			}

			form.submit();
		}, 0);
	}

	function replaceRowIndex(value, oldIndex, newIndex, attributeName) {
		var output = String(value);
		var oldToken = String(oldIndex);
		var newToken = String(newIndex);

		if ('name' === attributeName) {
			return output.replace(/\[components\]\[[^\]]+\]/, '[components][' + newToken + ']');
		}

		if ('id' === attributeName || 'for' === attributeName) {
			return output.replace(/^(cck-manual-layout-|cck-component-preview-)[^\s-]+(-)/, '$1' + newToken + '$2');
		}

		return output
			.replace(new RegExp('(cck_manual_layout\\[components\\]\\[)' + escapeRegExp(oldToken) + '(\\])', 'g'), '$1' + newToken + '$2')
			.replace(new RegExp('(cck-manual-layout-)' + escapeRegExp(oldToken) + '(-)', 'g'), '$1' + newToken + '$2');
	}

	function syncLayoutRowIndexes(rowsContainer) {
		var rows = Array.prototype.slice.call(rowsContainer.querySelectorAll('[data-layout-row]'));

		rows.forEach(function (row, rowIndex) {
			var oldIndex = row.getAttribute('data-layout-row-index') || '';
			var elements = Array.prototype.slice.call(row.querySelectorAll('[name], [id], [for]'));

			row.setAttribute('data-layout-row-index', String(rowIndex));
			row.setAttribute('data-layout-row-current-type', row.querySelector('[data-layout-row-type]') ? row.querySelector('[data-layout-row-type]').value : '');

			elements.forEach(function (element) {
				['name', 'id', 'for'].forEach(function (attributeName) {
					if (!element.hasAttribute(attributeName)) {
						return;
					}

					var currentValue = element.getAttribute(attributeName);
					var updatedValue = replaceRowIndex(currentValue, oldIndex, rowIndex, attributeName);

					if (updatedValue !== currentValue) {
						element.setAttribute(attributeName, updatedValue);
					}
				});
			});
		});
	}

	function syncLayoutRowState(row) {
		if (!row) {
			return;
		}

		var typeSelect = row.querySelector('[data-layout-row-type]');
		var fieldsets = Array.prototype.slice.call(row.querySelectorAll('[data-layout-fields]'));
		var badge = row.querySelector('.cck-layout-row__head-main .cck-admin-badge');
		var callback = row.querySelector('.cck-layout-row__callback');
		var fieldsContainer = row.querySelector('.cck-layout-row__fields');
		var selectedType = typeSelect ? typeSelect.value : '';
		var selectedLabel = '';
		var selectedFieldset = null;

		if (typeSelect && typeSelect.selectedIndex > -1) {
			selectedLabel = typeSelect.options[typeSelect.selectedIndex].textContent || typeSelect.value;
		}

		fieldsets.forEach(function (fieldset) {
			var isActive = fieldset.getAttribute('data-layout-fields') === selectedType;

			fieldset.hidden = !isActive;

			if (isActive) {
				selectedFieldset = fieldset;
			}
		});

		if (badge && selectedLabel) {
			badge.textContent = selectedLabel;
		}

		if (selectedFieldset) {
			var callbackCode = selectedFieldset.querySelector('legend code');

			if (callbackCode) {
				if (!callback) {
					callback = document.createElement('p');
					callback.className = 'description cck-layout-row__callback';
					if (fieldsContainer) {
						row.insertBefore(callback, fieldsContainer);
					}
				}

				callback.hidden = false;
				callback.innerHTML = '';

				var code = document.createElement('code');
				code.textContent = callbackCode.textContent || '';
				callback.appendChild(code);
			} else if (callback) {
				callback.hidden = true;
			}
		} else if (callback) {
			callback.hidden = true;
		}
	}

	function syncLayoutBuilder(form) {
		var rowsContainer = form.querySelector('[data-layout-rows]');

		if (!rowsContainer) {
			return;
		}

		syncLayoutRowIndexes(rowsContainer);

		Array.prototype.slice.call(rowsContainer.querySelectorAll('[data-layout-row]')).forEach(function (row) {
			syncLayoutRowState(row);
		});
	}

	function getLayoutTemplateMarkup() {
		var template = document.getElementById('cck-layout-row-template');

		return template ? template.innerHTML : '';
	}

	function addLayoutRow(form) {
		var rowsContainer = form.querySelector('[data-layout-rows]');
		var templateMarkup = getLayoutTemplateMarkup();

		if (!rowsContainer || !templateMarkup) {
			return;
		}

		var nextIndex = rowsContainer.querySelectorAll('[data-layout-row]').length;
		var rowMarkup = templateMarkup.replace(/__INDEX__/g, String(nextIndex));

		rowsContainer.insertAdjacentHTML('beforeend', rowMarkup);
		syncLayoutBuilder(form);
		submitLayoutForm(form);
	}

	function moveLayoutRow(form, row, direction) {
		var rowsContainer = form.querySelector('[data-layout-rows]');
		var targetRow = direction > 0 ? row.nextElementSibling : row.previousElementSibling;

		if (!rowsContainer || !targetRow || !targetRow.matches('[data-layout-row]')) {
			return;
		}

		if (direction > 0) {
			rowsContainer.insertBefore(targetRow, row);
		} else {
			rowsContainer.insertBefore(row, targetRow);
		}

		syncLayoutBuilder(form);
		submitLayoutForm(form);
	}

	function removeLayoutRow(form, row) {
		if (!row) {
			return;
		}

		row.parentNode.removeChild(row);
		syncLayoutBuilder(form);
		submitLayoutForm(form);
	}

	function init() {
		var adminRoot = document.querySelector('.cck-admin-dashboard');
		var previewCanvas = document.querySelector('[data-preview-canvas]');
		var layoutForms = Array.prototype.slice.call(document.querySelectorAll('[data-layout-builder]'));

		if (!adminRoot) {
			adminRoot = null;
		}

		if (previewCanvas) {
			var previewButtons = Array.prototype.slice.call(document.querySelectorAll('.cck-admin-preview-tabs [data-preview-mode]'));

			if (previewButtons.length) {
				var setPreviewMode = function (mode) {
					previewCanvas.classList.remove('cck-preview-desktop', 'cck-preview-tablet', 'cck-preview-mobile');
					previewCanvas.classList.add('cck-preview-' + mode);

					previewButtons.forEach(function (button) {
						var isActive = button.getAttribute('data-preview-mode') === mode;

						button.classList.toggle('is-active', isActive);
						button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
					});
				};

				previewButtons.forEach(function (button) {
					button.addEventListener('click', function () {
						setPreviewMode(button.getAttribute('data-preview-mode'));
					});
				});

				setPreviewMode('desktop');
			}
		}

		if (layoutForms.length) {
			layoutForms.forEach(function (form) {
				var rowsContainer = form.querySelector('[data-layout-rows]');

				if (!rowsContainer) {
					return;
				}

				syncLayoutBuilder(form);

				form.addEventListener('click', function (event) {
					var addButton = event.target.closest('[data-layout-add]');
					var removeButton = event.target.closest('[data-layout-remove]');
					var moveButton = event.target.closest('[data-layout-move]');
					var row = event.target.closest('[data-layout-row]');

					if (addButton && form.contains(addButton)) {
						event.preventDefault();
						addLayoutRow(form);
						return;
					}

					if (removeButton && form.contains(removeButton)) {
						event.preventDefault();
						removeLayoutRow(form, row);
						return;
					}

					if (moveButton && form.contains(moveButton) && row) {
						event.preventDefault();
						moveLayoutRow(form, row, 'up' === moveButton.getAttribute('data-layout-move') ? -1 : 1);
					}
				});

				form.addEventListener('change', function (event) {
					var row = event.target.closest('[data-layout-row]');

					if (!row || !form.contains(row)) {
						return;
					}

					if (event.target.matches('[data-layout-row-type]')) {
						syncLayoutBuilder(form);
						submitLayoutForm(form);
					}
				});
			});
		}

		if (adminRoot) {
			adminRoot.addEventListener('click', function (event) {
				var button = event.target.closest('.cck-admin-copy');

				if (!button || !adminRoot.contains(button)) {
					return;
				}

				copyText(button.getAttribute('data-cck-copy') || '', button);
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
