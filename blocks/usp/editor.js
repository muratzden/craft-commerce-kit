(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;

    blocks.registerBlockType('craft-commerce-kit/usp', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({
                className: 'cck-component cck-usp'
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
                            title: 'First USP Item',
                            initialOpen: true
                        },
                        el(TextControl, {
                            label: 'First Item Title',
                            value: attributes.item_one_title,
                            onChange: function (value) {
                                setAttributes({ item_one_title: value });
                            }
                        }),
                        el(TextareaControl, {
                            label: 'First Item Text',
                            value: attributes.item_one_text,
                            onChange: function (value) {
                                setAttributes({ item_one_text: value });
                            }
                        })
                    ),
                    el(
                        PanelBody,
                        {
                            title: 'Second USP Item',
                            initialOpen: false
                        },
                        el(TextControl, {
                            label: 'Second Item Title',
                            value: attributes.item_two_title,
                            onChange: function (value) {
                                setAttributes({ item_two_title: value });
                            }
                        }),
                        el(TextareaControl, {
                            label: 'Second Item Text',
                            value: attributes.item_two_text,
                            onChange: function (value) {
                                setAttributes({ item_two_text: value });
                            }
                        })
                    ),
                    el(
                        PanelBody,
                        {
                            title: 'Third USP Item',
                            initialOpen: false
                        },
                        el(TextControl, {
                            label: 'Third Item Title',
                            value: attributes.item_three_title,
                            onChange: function (value) {
                                setAttributes({ item_three_title: value });
                            }
                        }),
                        el(TextareaControl, {
                            label: 'Third Item Text',
                            value: attributes.item_three_text,
                            onChange: function (value) {
                                setAttributes({ item_three_text: value });
                            }
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
                        ['item_one_title', 'item_one_text'],
                        ['item_two_title', 'item_two_text'],
                        ['item_three_title', 'item_three_text']
                    ].map(function (keys, index) {
                        return el(
                            'article',
                            {
                                className: 'cck-usp-item',
                                key: index
                            },
                            el('h3', null, attributes[keys[0]]),
                            el('p', null, attributes[keys[1]])
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
