(function (element, components) {
    'use strict';

    var el = element.createElement;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var Button = components.Button;
    var BaseControl = components.BaseControl;

    function setAttribute(settingId, value, setAttributes) {
        var nextAttributes = {};
        nextAttributes[settingId] = value;
        setAttributes(nextAttributes);
    }

    function renderImageControl(settingId, setting, attributes, setAttributes) {
        var value = attributes[settingId] || '';

        function openMediaLibrary() {
            var frame = window.wp.media({
                title: setting.label || 'Select Image',
                button: {
                    text: 'Use this image'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            frame.on('select', function () {
                var attachment = frame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();

                setAttribute(
                    settingId,
                    attachment.url || '',
                    setAttributes
                );
            });

            frame.open();
        }

        return el(
            BaseControl,
            {
                key: settingId,
                label: setting.label || settingId,
                help: setting.description || ''
            },
            value
                ? el('img', {
                    src: value,
                    alt: '',
                    style: {
                        display: 'block',
                        width: '100%',
                        height: 'auto',
                        marginBottom: '12px'
                    }
                })
                : null,
            el(
                'div',
                null,
                el(
                    Button,
                    {
                        variant: 'secondary',
                        onClick: openMediaLibrary
                    },
                    value ? 'Change Image' : 'Select Image'
                ),
                value
                    ? el(
                        Button,
                        {
                            variant: 'tertiary',
                            isDestructive: true,
                            onClick: function () {
                                setAttribute(settingId, '', setAttributes);
                            }
                        },
                        'Remove Image'
                    )
                    : null
            )
        );
    }

    function renderControl(settingId, setting, attributes, setAttributes) {
        var controlProps = {
            key: settingId,
            label: setting.label || settingId,
            help: setting.description || '',
            value: attributes[settingId],
            onChange: function (value) {
                setAttribute(settingId, value, setAttributes);
            }
        };

        if ('image_url' === settingId) {
            return renderImageControl(
                settingId,
                setting,
                attributes,
                setAttributes
            );
        }

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
                setAttribute(settingId, Number(value), setAttributes);
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
