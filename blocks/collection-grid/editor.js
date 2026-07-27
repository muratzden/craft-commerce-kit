(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var RichText = blockEditor.RichText;
    var PanelBody = components.PanelBody;
    var renderControl = window.cckBlockEditor.renderControl;
    var demoAssetUrl = window.cckBlockEditorDemoAssetUrl || '';
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/collection-grid']
    ) || {};

    function toPlainText(value) {
        var wrapper = document.createElement('div');

        wrapper.innerHTML = value || '';

        return wrapper.textContent || wrapper.innerText || '';
    }

    function cleanText(value) {
        return toPlainText(value)
            .replace(/[|,]/g, ' ')
            .trim();
    }

    function cleanUrl(value) {
        return toPlainText(value)
            .replace(/[|,]/g, '')
            .trim();
    }

    function cleanFilename(value) {
        return toPlainText(value)
            .replace(/[^a-zA-Z0-9._-]/g, '')
            .trim();
    }

    function parseItems(value) {
        return (value || '')
            .split('|')
            .map(function (row) {
                var parts = row.split(',');
                var label = cleanText(parts.shift() || '');
                var subtitle = cleanText(parts.shift() || '');
                var url = cleanUrl(parts.shift() || '#');
                var image = cleanFilename(parts.join(',') || 'featured.webp');

                return {
                    label: label,
                    subtitle: subtitle || 'Explore the edit',
                    url: url || '#',
                    image: image || 'featured.webp'
                };
            })
            .filter(function (item) {
                return item.label;
            });
    }

    function serializeItems(items) {
        return items.map(function (item) {
            return [
                cleanText(item.label),
                cleanText(item.subtitle),
                cleanUrl(item.url),
                cleanFilename(item.image)
            ].join(',');
        }).join('|');
    }

    function getImageUrl(filename) {
        var cleanFile = cleanFilename(filename);

        if (!demoAssetUrl || !cleanFile) {
            return '';
        }

        return demoAssetUrl + encodeURIComponent(cleanFile);
    }

    blocks.registerBlockType('craft-commerce-kit/collection-grid', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var items = parseItems(attributes.items);
            var columns = parseInt(attributes.columns, 10);

            columns = Math.min(4, Math.max(1, columns || 2));

            var blockProps = useBlockProps({
                className: 'cck-section cck-component cck-collection-grid',
                style: {
                    '--cck-grid-columns': columns
                }
            });

            function updateItem(index, key, value) {
                var updatedItems = items.map(function (item, itemIndex) {
                    if (itemIndex !== index) {
                        return item;
                    }

                    return {
                        label: key === 'label' ? value : item.label,
                        subtitle: key === 'subtitle' ? value : item.subtitle,
                        url: item.url,
                        image: item.image
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
                            title: 'Collection Grid Settings',
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
                        {
                            className: 'cck-container cck-collection-grid__inner'
                        },
                        items.map(function (item, index) {
                            var imageUrl = getImageUrl(item.image);

                            return el(
                                'div',
                                {
                                    className: 'cck-collection-card',
                                    key: index
                                },
                                el(
                                    'span',
                                    {
                                        className: 'cck-collection-card__media'
                                    },
                                    imageUrl ? el('img', {
                                        src: imageUrl,
                                        alt: item.label
                                    }) : null
                                ),
                                el(
                                    'span',
                                    {
                                        className: 'cck-collection-card__overlay',
                                        'aria-hidden': 'true'
                                    }
                                ),
                                el(
                                    'span',
                                    {
                                        className: 'cck-collection-card__content'
                                    },
                                    el(
                                        'span',
                                        {
                                            className: 'cck-collection-card__eyebrow'
                                        },
                                        'Collection'
                                    ),
                                    el(RichText, {
                                        tagName: 'strong',
                                        value: item.label,
                                        allowedFormats: [],
                                        placeholder: 'Collection title...',
                                        onChange: function (value) {
                                            updateItem(index, 'label', value);
                                        }
                                    }),
                                    el(RichText, {
                                        tagName: 'span',
                                        value: item.subtitle,
                                        allowedFormats: [],
                                        placeholder: 'Collection description...',
                                        onChange: function (value) {
                                            updateItem(index, 'subtitle', value);
                                        }
                                    }),
                                    el(
                                        'span',
                                        {
                                            className: 'cck-collection-card__arrow',
                                            'aria-hidden': 'true'
                                        },
                                        el(
                                            'span',
                                            {
                                                className: 'dashicons dashicons-arrow-right-alt2'
                                            }
                                        )
                                    )
                                )
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
