(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var renderControl = window.cckBlockEditor.renderControl;
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/section-title']
    ) || {};

    blocks.registerBlockType('craft-commerce-kit/section-title', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var align = attributes.align || 'left';
            var surface = attributes.surface || 'transparent';

            var allowedAlignments = [
                'left',
                'center',
                'right'
            ];

            var allowedSurfaces = [
                'transparent',
                'background',
                'surface',
                'surface-alt',
                'dark'
            ];

            if (allowedAlignments.indexOf(align) === -1) {
                align = 'left';
            }

            if (allowedSurfaces.indexOf(surface) === -1) {
                surface = 'transparent';
            }

            var blockProps = useBlockProps({
                className: [
                    'cck-section-title',
                    'cck-section-title--' + align,
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
                    el(
                    'p',
                    { className: 'cck-eyebrow' },
                    attributes.eyebrow || 'Eyebrow'
                ),

                el(
                    'h2',
                    null,
                    attributes.title || 'Section Title'
                ),

                el(
                    'p',
                    null,
                    attributes.text || 'Describe this section...'
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
