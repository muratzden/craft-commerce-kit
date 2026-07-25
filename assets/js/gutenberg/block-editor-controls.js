(function (element, components) {
    'use strict';

    var el = element.createElement;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;

    function renderControl(settingId, setting, attributes, setAttributes) {
        var controlProps = {
            key: settingId,
            label: setting.label || settingId,
            help: setting.description || '',
            value: attributes[settingId],
            onChange: function (value) {
                var nextAttributes = {};
                nextAttributes[settingId] = value;
                setAttributes(nextAttributes);
            }
        };

        if ('textarea' === setting.type) {
            return el(TextareaControl, controlProps);
        }

        if ('checkbox' === setting.type) {
            controlProps.checked = Boolean(attributes[settingId]);
            delete controlProps.value;

            return el(ToggleControl, controlProps);
        }

        if ('select' === setting.type) {
            controlProps.options = Object.keys(setting.options || {}).map(function (value) {
                return {
                    label: setting.options[value],
                    value: value
                };
            });

            return el(SelectControl, controlProps);
        }

        if ('number' === setting.type) {
            controlProps.type = 'number';
            controlProps.onChange = function (value) {
                var nextAttributes = {};
                nextAttributes[settingId] = Number(value);
                setAttributes(nextAttributes);
            };
        }

        if ('url' === setting.type) {
            controlProps.type = 'url';
        }

        return el(TextControl, controlProps);
    }

    window.cckBlockEditor = window.cckBlockEditor || {};
    window.cckBlockEditor.renderControl = renderControl;
}(
    window.wp.element,
    window.wp.components
));
