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
        window.cckBlockEditorSettings['craft-commerce-kit/trust-block']
    ) || {};

    function toPlainText(value) {
        var wrapper = document.createElement('div');

        wrapper.innerHTML = value || '';

        return (wrapper.textContent || wrapper.innerText || '')
            .replace(/\|/g, ' ')
            .replace(/::/g, ':');
    }
    function parseItems(value) {
        return (value || '')
            .split('|')
            .map(function (item) {
                var parts = item.trim().split('::');
                var title = toPlainText((parts.shift() || '').trim());
                var text = toPlainText(parts.join('::').trim());

                return {
                    title: title,
                    text: text || title
                };
            })
            .filter(function (item) {
                return item.title || item.text;
            });
    }

    function serializeItems(items) {
        return items.map(function (item) {
            return toPlainText(item.title) + '::' + toPlainText(item.text);
        }).join('|');
    }

    blocks.registerBlockType('craft-commerce-kit/trust-block', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var items = parseItems(attributes.items);
            var blockProps = useBlockProps({
                className: 'cck-section cck-component cck-trust'
            });

            function updateItem(index, key, value) {
                var updatedItems = items.map(function (item, itemIndex) {
                    if (itemIndex !== index) {
                        return item;
                    }

                    return {
                        title: key === 'title' ? value : item.title,
                        text: key === 'text' ? value : item.text
                    };
                });

                setAttributes({
                    items: serializeItems(updatedItems)
                });
            }

            return el(
                element.Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        {
                            title: 'Trust Block Settings',
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
                        { className: 'cck-container' },
                        el(
                            'div',
                            { className: 'cck-trust__grid' },
                            items.map(function (item, index) {
                                return el(
                                    'article',
                                    {
                                        className: 'cck-trust__item',
                                        key: index
                                    },
                                    el(
                                        'span',
                                        {
                                            className: 'cck-trust__icon',
                                            'aria-hidden': 'true'
                                        },
                                        el(
                                            'span',
                                            {
                                                className: 'dashicons dashicons-shield'
                                            }
                                        )
                                    ),
                                    el(RichText, {
                                        tagName: 'h3',
                                        value: item.title,
                                        allowedFormats: [],
                                        placeholder: 'Trust title...',
                                        onChange: function (value) {
                                            updateItem(index, 'title', value);
                                        }
                                    }),
                                    el(RichText, {
                                        tagName: 'p',
                                        value: item.text,
                                        allowedFormats: [],
                                        placeholder: 'Trust description...',
                                        onChange: function (value) {
                                            updateItem(index, 'text', value);
                                        }
                                    })
                                );
                            })
                        )
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
