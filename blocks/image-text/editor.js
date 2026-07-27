(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var RichText = blockEditor.RichText;
    var MediaUpload = blockEditor.MediaUpload;
    var MediaUploadCheck = blockEditor.MediaUploadCheck;
    var PanelBody = components.PanelBody;
    var Button = components.Button;
    var renderControl = window.cckBlockEditor.renderControl;
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/image-text']
    ) || {};

    blocks.registerBlockType('craft-commerce-kit/image-text', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var reverse = Boolean(attributes.reverse);
            var surface = attributes.surface || 'transparent';

            var allowedSurfaces = [
                'transparent',
                'background',
                'surface',
                'surface-alt',
                'dark'
            ];

            if (allowedSurfaces.indexOf(surface) === -1) {
                surface = 'transparent';
            }

            var classes = [
                'cck-section',
                'cck-image-text',
                'cck-surface',
                'cck-surface--' + surface
            ];

            if (reverse) {
                classes.push('cck-image-text--reverse');
            }

            var blockProps = useBlockProps({
                className: classes.join(' ')
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
                            title: 'Image Text Settings',
                            initialOpen: true
                        },
                        Object.keys(editorSettings)
                            .filter(function (settingId) {
                                return [
                                    'image_id',
                                    'image_url'
                                ].indexOf(settingId) === -1;
                            })
                            .map(function (settingId) {
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
                        { className: 'cck-container cck-image-text__inner' },
                        el(
                            'div',
                            { className: 'cck-image-text__media' },
                            el(
                                MediaUploadCheck,
                                null,
                                el(MediaUpload, {
                                    allowedTypes: ['image'],
                                    value: attributes.image_id || 0,
                                    onSelect: function (media) {
                                        setAttributes({
                                            image_id: media.id || 0,
                                            image_url: media.url || ''
                                        });
                                    },
                                    render: function (obj) {
                                        if (attributes.image_url) {
                                            return el(
                                                element.Fragment,
                                                null,
                                                el('img', {
                                                    src: attributes.image_url,
                                                    alt: '',
                                                    loading: 'lazy',
                                                    onClick: obj.open,
                                                    style: {
                                                        cursor: 'pointer'
                                                    }
                                                }),
                                                el(
                                                    'div',
                                                    {
                                                        style: {
                                                            display: 'flex',
                                                            gap: '8px',
                                                            justifyContent: 'center',
                                                            marginTop: '12px'
                                                        }
                                                    },
                                                    el(
                                                        Button,
                                                        {
                                                            variant: 'secondary',
                                                            onClick: obj.open
                                                        },
                                                        'Replace image'
                                                    ),
                                                    el(
                                                        Button,
                                                        {
                                                            variant: 'tertiary',
                                                            isDestructive: true,
                                                            onClick: function () {
                                                                setAttributes({
                                                                    image_id: 0,
                                                                    image_url: ''
                                                                });
                                                            }
                                                        },
                                                        'Remove image'
                                                    )
                                                )
                                            );
                                        }

                                        return el(
                                            'div',
                                            {
                                                className: 'cck-placeholder cck-placeholder--image-text'
                                            },
                                            el(
                                                Button,
                                                {
                                                    variant: 'primary',
                                                    onClick: obj.open
                                                },
                                                'Select image'
                                            )
                                        );
                                    }
                                })
                            )
                        ),
                        el(
                            'div',
                            { className: 'cck-image-text__content' },
                            el(RichText, {
                                tagName: 'h2',
                                value: attributes.title,
                                placeholder: 'Image Text title...',
                                allowedFormats: [],
                                onChange: function (value) {
                                    setAttributes({
                                        title: value
                                    });
                                }
                            }),
                            el(RichText, {
                                tagName: 'p',
                                value: attributes.text,
                                placeholder: 'Image Text description...',
                                allowedFormats: [],
                                onChange: function (value) {
                                    setAttributes({
                                        text: value
                                    });
                                }
                            }),
                            attributes.button_label
                                ? el(RichText, {
                                    tagName: 'span',
                                    className: 'cck-button cck-button--primary',
                                    value: attributes.button_label,
                                    placeholder: 'Button label...',
                                    allowedFormats: [],
                                    onChange: function (value) {
                                        setAttributes({
                                            button_label: value
                                        });
                                    }
                                })
                                : null
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
