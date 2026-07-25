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
