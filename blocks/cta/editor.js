(function (blocks, element, blockEditor, components) {
    'use strict';

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var renderControl = window.cckBlockEditor.renderControl;
    var editorSettings = (
        window.cckBlockEditorSettings &&
        window.cckBlockEditorSettings['craft-commerce-kit/cta']
    ) || {};

    blocks.registerBlockType('craft-commerce-kit/cta', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({
                className: 'cck-section cck-cta'
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
                            title: 'CTA Settings',
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
                        { className: 'cck-container cck-cta__inner' },
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
