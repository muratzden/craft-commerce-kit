(function (element, components, blockEditor) {
    'use strict';

    var el = element.createElement;
    var useState = element.useState;

    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var Button = components.Button;
    var BaseControl = components.BaseControl;
    var Popover = components.Popover;

    var LinkControl = blockEditor.LinkControl;

    function setAttribute(settingId, value, setAttributes) {
        var nextAttributes = {};
        nextAttributes[settingId] = value;
        setAttributes(nextAttributes);
    }

    function ImagePickerControl(props) {
        var settingId = props.settingId;
        var setting = props.setting;
        var attributes = props.attributes;
        var setAttributes = props.setAttributes;
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
                {
                    style: {
                        display: 'flex',
                        flexWrap: 'wrap',
                        gap: '8px'
                    }
                },
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

    function LinkPickerControl(props) {
        var settingId = props.settingId;
        var setting = props.setting;
        var attributes = props.attributes;
        var setAttributes = props.setAttributes;
        var value = attributes[settingId] || '';
        var state = useState(false);
        var isOpen = state[0];
        var setIsOpen = state[1];

        return el(
            BaseControl,
            {
                label: setting.label || settingId,
                help: setting.description || ''
            },
            el(TextControl, {
                type: 'url',
                value: value,
                onChange: function (nextValue) {
                    setAttribute(settingId, nextValue, setAttributes);
                }
            }),
            el(
                'div',
                {
                    style: {
                        position: 'relative',
                        display: 'flex',
                        flexWrap: 'wrap',
                        gap: '8px'
                    }
                },
                el(
                    Button,
                    {
                        variant: 'secondary',
                        onClick: function () {
                            setIsOpen(!isOpen);
                        }
                    },
                    value ? 'Change Link' : 'Select Link'
                ),
                value
                    ? el(
                        Button,
                        {
                            variant: 'tertiary',
                            isDestructive: true,
                            onClick: function () {
                                setAttribute(settingId, '', setAttributes);
                                setIsOpen(false);
                            }
                        },
                        'Remove Link'
                    )
                    : null,
                isOpen
                    ? el(
                        Popover,
                        {
                            placement: 'left-start',
                            onClose: function () {
                                setIsOpen(false);
                            }
                        },
                        el(LinkControl, {
                            value: {
                                url: value
                            },
                            onChange: function (nextValue) {
                                setAttribute(
                                    settingId,
                                    nextValue.url || '',
                                    setAttributes
                                );
                            },
                            onRemove: function () {
                                setAttribute(settingId, '', setAttributes);
                                setIsOpen(false);
                            }
                        })
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
            return el(ImagePickerControl, {
                key: settingId,
                settingId: settingId,
                setting: setting,
                attributes: attributes,
                setAttributes: setAttributes
            });
        }

        if (
            'url' === setting.type &&
            /_url$/.test(settingId)
        ) {
            return el(LinkPickerControl, {
                key: settingId,
                settingId: settingId,
                setting: setting,
                attributes: attributes,
                setAttributes: setAttributes
            });
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
    window.wp.components,
    window.wp.blockEditor
));
