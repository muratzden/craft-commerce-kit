(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/cta']
    ) || {};

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

    blocks.registerBlockType('craft-commerce-kit/cta', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({
                className: 'cck-section cck-cta'
            });

            return el(
                element.Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        {
                            title: 'CTA Settings',
                            initialOpen: true
                        },
                        Object.keys(editorSettings).map(function (settingId) {
                            return renderControl(
                                settingId,
                                editorSettings[settingId],
                                attributes,
                                setAttributes
                            );
                        })
                    )
                ),
                el(
                    'section',
                    blockProps,
                    el(
                        'div',
                        { className: 'cck-container cck-cta__inner' },
                        attributes.title
                            ? el('h2', null, attributes.title)
                            : null,
                        attributes.text
                            ? el('p', null, attributes.text)
                            : null,
                        attributes.button_label
                            ? el(
                                'span',
                                {
                                    className: 'cck-button cck-button--primary',
                                    role: 'presentation'
                                },
                                attributes.button_label
                            )
                            : null
                    )
                )
            );
        },

        save: function () {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components
));
