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
        window.cckBlockEditorSettings['craft-commerce-kit/hero']
    ) || {};

    blocks.registerBlockType('craft-commerce-kit/hero', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var surface = attributes.surface || 'background';

            var allowedSurfaces = [
                'transparent',
                'background',
                'surface',
                'surface-alt',
                'dark'
            ];

            if (allowedSurfaces.indexOf(surface) === -1) {
                surface = 'background';
            }

            var blockProps = useBlockProps({
                className: [
                    'cck-section',
                    'cck-hero',
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
                            title: 'Hero Settings',
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
                            { className: 'cck-hero__inner' },
                            el(
                                'div',
                                { className: 'cck-hero__content' },
                                el(
                                    'div',
                                    { className: 'cck-hero__eyebrow-wrap' },
                                    el(RichText, {
                                        tagName: 'span',
                                        className: 'cck-eyebrow',
                                        value: attributes.eyebrow,
                                        placeholder: 'Eyebrow…',
                                        allowedFormats: [],
                                        onChange: function (value) {
                                            setAttributes({
                                                eyebrow: value
                                            });
                                        }
                                    })
                                ),
                                el(RichText, {
                                    tagName: 'h1',
                                    className: 'cck-hero__title',
                                    value: attributes.title,
                                    placeholder: 'Hero title…',
                                    allowedFormats: [],
                                    onChange: function (value) {
                                        setAttributes({
                                            title: value
                                        });
                                    }
                                }),
                                el(
                                    'div',
                                    { className: 'cck-hero__description' },
                                    el(RichText, {
                                        tagName: 'p',
                                        className: 'cck-hero__text',
                                        value: attributes.text,
                                        placeholder: 'Hero description…',
                                        allowedFormats: [],
                                        onChange: function (value) {
                                            setAttributes({
                                                text: value
                                            });
                                        }
                                    })
                                ),
                                (
                                    attributes.primary_label ||
                                    attributes.secondary_label
                                )
                                    ? el(
                                        'div',
                                        { className: 'cck-hero__actions' },
                                        attributes.primary_label
                                            ? el(RichText, {
                                                tagName: 'span',
                                                className: 'cck-button cck-button--primary',
                                                value: attributes.primary_label,
                                                placeholder: 'Primary button…',
                                                allowedFormats: [],
                                                onChange: function (value) {
                                                    setAttributes({
                                                        primary_label: value
                                                    });
                                                }
                                            })
                                            : null,
                                        attributes.secondary_label
                                            ? el(RichText, {
                                                tagName: 'span',
                                                className: 'cck-button cck-button--secondary',
                                                value: attributes.secondary_label,
                                                placeholder: 'Secondary button…',
                                                allowedFormats: [],
                                                onChange: function (value) {
                                                    setAttributes({
                                                        secondary_label: value
                                                    });
                                                }
                                            })
                                            : null
                                    )
                                    : null
                            ),
                            el(
                                'div',
                                { className: 'cck-hero__media' },
                                el(
                                    'div',
                                    { className: 'cck-hero__media-frame' },
                                    el(
                                        MediaUploadCheck,
                                        null,
                                        el(MediaUpload, {
                                            allowedTypes: ['image'],
                                            value: attributes.image_id || 0,

                                            onSelect: function (media) {
                                                setAttributes({
                                                    image_id: media.id,
                                                    image_url: media.url
                                                });
                                            },

                                            render: function (obj) {

                                                if (attributes.image_url) {
                                                    return el('img', {
                                                        className: 'cck-hero__image',
                                                        src: attributes.image_url,
                                                        alt: '',
                                                        loading: 'lazy',
                                                        onClick: obj.open,
                                                        style: {
                                                            cursor: 'pointer'
                                                        }
                                                    });
                                                }

                                                return el(
                                                    Button,
                                                    {
                                                        variant: 'primary',
                                                        onClick: obj.open
                                                    },
                                                    'Select image'
                                                );
                                            }
                                        })
                                    )
                                )
                            )
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
