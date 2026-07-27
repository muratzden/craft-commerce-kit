(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var RichText = blockEditor.RichText;
    var PanelBody = components.PanelBody;
    var renderControl = window.cckBlockEditor.renderControl;
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/usp']
    ) || {};

    blocks.registerBlockType('craft-commerce-kit/usp', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var surface = attributes.surface || 'surface';

            var allowedSurfaces = [
                'transparent',
                'background',
                'surface',
                'surface-alt',
                'dark'
            ];

            if (allowedSurfaces.indexOf(surface) === -1) {
                surface = 'surface';
            }

            var blockProps = useBlockProps({
                className: [
                    'cck-section',
                    'cck-component',
                    'cck-usp',
                    'cck-surface',
                    'cck-surface--' + surface
                ].join(' ')
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
                            title: 'USP Settings',
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
                        { className: 'cck-container cck-usp-grid' },
                       [
                            ['item_one_icon', 'item_one_title', 'item_one_text'],
                            ['item_two_icon', 'item_two_title', 'item_two_text'],
                            ['item_three_icon', 'item_three_title', 'item_three_text']
                        ].map(function (keys, index) {
                            return el(
                                'article',
                                {
                                    className: 'cck-usp-item',
                                    key: index
                                },
                                el(
                                    'span',
                                    {
                                        className: 'dashicons dashicons-' + (attributes[keys[0]] || 'star-filled') + ' cck-usp-item__icon',
                                        'aria-hidden': 'true'
                                    }
                                ),
                               el(RichText, {
                                    tagName: 'h3',
                                    value: attributes[keys[1]],
                                    allowedFormats: [],
                                    placeholder: 'USP title…',
                                    onChange: function (value) {
                                        var update = {};
                                        update[keys[1]] = value;
                                        setAttributes(update);
                                    }
                                }),
                                el(RichText, {
                                    tagName: 'p',
                                    value: attributes[keys[2]],
                                    allowedFormats: [],
                                    placeholder: 'USP text…',
                                    onChange: function (value) {
                                        var update = {};
                                        update[keys[2]] = value;
                                        setAttributes(update);
                                    }
                                })
                            );
                        })
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
