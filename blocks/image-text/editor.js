(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
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
                        { className: 'cck-container cck-image-text__inner' },
                        el(
                            'div',
                            { className: 'cck-image-text__media' },
                            attributes.image_url
                                ? el('img', {
                                    src: attributes.image_url,
                                    alt: '',
                                    loading: 'lazy'
                                })
                                : el('div', {
                                    className: 'cck-placeholder cck-placeholder--image-text'
                                })
                        ),
                        el(
                            'div',
                            { className: 'cck-image-text__content' },
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
