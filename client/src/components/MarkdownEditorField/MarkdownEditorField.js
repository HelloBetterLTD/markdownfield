'use strict';

import React from 'react';
import ReactDOM from 'react-dom';
const SimpleMDE = require('simplemde');
import ReactSimpleMDE from 'react-simplemde-editor';
import InsertEmbedModal from '../../../../../asset-admin/client/src/components/InsertEmbedModal/InsertEmbedModal';
import { provideInjector } from 'lib/Injector';
const InjectableInsertEmbedModal = provideInjector(InsertEmbedModal);

class MarkdownEditorField extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            toolbar: [
                {
                    name: "heading",
                    action: SimpleMDE.toggleHeadingSmaller,
                    className: "fa fa-header",
                    title: "Heading",
                },
                {
                    name: "bold",
                    action: SimpleMDE.toggleBold,
                    className: "fa fa-bold",
                    title: "Bold",
                },
                {
                    name: "italic",
                    action: SimpleMDE.toggleItalic,
                    className: "fa fa-italic",
                    title: "Italic",
                },
                {
                    name: "strikethrough",
                    action: SimpleMDE.toggleStrikethrough,
                    className: "fa fa-strikethrough",
                    title: "Strikethrough",
                },
                "|",
                {
                    name: "quote",
                    action: SimpleMDE.toggleBlockquote,
                    className: "fa fa-quote-left",
                    title: "Quote",
                },
                {
                    name: "unordered-list",
                    action: SimpleMDE.toggleUnorderedList,
                    className: "fa fa-list-ul",
                    title: "Generic List",
                },
                {
                    name: "ordered-list",
                    action: SimpleMDE.toggleOrderedList,
                    className: "fa fa-list-ol",
                    title: "Ordered List",
                },
                "|",
                {
                    name: "link",
                    action: SimpleMDE.drawLink,
                    className: "fa fa-link",
                    title: "Create Link",
                },
                {
                    name: "image",
                    action: SimpleMDE.drawImage,
                    className: "fa fa-picture-o",
                    title: "Insert Image",
                },
                "|",
                {
                    name: "preview",
                    action: SimpleMDE.togglePreview,
                    className: "fa fa-eye no-disable",
                    title: "Toggle Preview",
                },
                {
                    name: "side-by-side",
                    action: SimpleMDE.toggleSideBySide,
                    className: "fa fa-columns no-disable no-mobile",
                    title: "Toggle Side by Side",
                },
                {
                    name: "fullscreen",
                    action: SimpleMDE.toggleFullScreen,
                    className: "fa fa-arrows-alt no-disable no-mobile",
                    title: "Toggle Fullscreen",
                },
                "|",
                {
                    name: "guide",
                    action: 'https://simplemde.com/markdown-guide',
                    className: "fa fa-question-circle",
                    title: "Markdown Guide",
                },
            ]
        };
    }

    handleChange(value) {
        this.props.textarea.value = value;
    }

    render() {
        let showIcons = ["code", "table"];


        return (
            <div className="editor-container">
                <ReactSimpleMDE
                    value = {this.props.textarea.value}
                    onChange={this.handleChange.bind(this)}
                    options={{
                        spellChecker: true,
                        dragDrop: false,
                        keyMap: "sublime",
                        toolbar: this.state.toolbar,
                        showIcons: {showIcons}
                    }}
                    ></ReactSimpleMDE>
            </div>
        );
    }
}

jQuery.entwine('ss', ($) => {
    $('.js-markdown-container:visible').entwine({

        onunmatch() {
            this._super();
            ReactDOM.unmountComponentAtNode(this[0]);
        },

        onmatch() {
            this._super();
            this.refresh();
        },

        refresh() {
            let textArea = $(this).parent().find('textarea')[0];


            ReactDOM.render(
                <MarkdownEditorField textarea={textArea}></MarkdownEditorField>,
                this[0]
            );
        }
    });
});