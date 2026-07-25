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
        window.cckBlockEditorSettings['craft-commerce-kit/section-title']
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

        return el(TextControl, controlProps);
    }

    blocks.registerBlockType('craft-commerce-kit/section-title', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var align = attributes.align || 'left';
            var blockProps = useBlockProps({
                className: 'cck-section-title cck-section-title--' + align
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
                            title: 'Section Title Settings',
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
                    'div',
                    blockProps,
                    attributes.eyebrow
                        ? el('p', { className: 'cck-eyebrow' }, attributes.eyebrow)
                        : null,
                    attributes.title
                        ? el('h2', null, attributes.title)
                        : null,
                    attributes.text
                        ? el('p', null, attributes.text)
                        : null
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
