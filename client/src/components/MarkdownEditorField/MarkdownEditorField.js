'use strict';

import React from 'react';
import ReactDOM from 'react-dom';
const SimpleMDE = require('simplemde');
import ReactSimpleMDE from 'react-simplemde-editor';
import { provideInjector } from 'lib/Injector';
import jQuery from 'jquery';

const markdownConfigs = {

}

jQuery.entwine('ss', ($) => {


    markdownConfigs.toolbar = [
            {
                name: "heading",
                action: SimpleMDE.toggleHeadingSmaller,
                className: "fa fa-header",
                title: "Heading HTML",
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
                /*
                 action: function() {

                 let dialog = jQuery('#insert-link-markdown-react__dialog-wrapper');

                 if (!dialog.length) {
                 dialog = jQuery('<div id="insert-link-markdown-react__dialog-wrapper" class="insert-link__md-dialog-wrapper" />');
                 jQuery('body').append(dialog);
                 dialog.dialog({
                 autoOpen: false
                 });
                 }

                 // dialog.setElement(this);
                 dialog.dialog("open");
                 // $();
                 // InsertLinkModal

                 },*/
                className: "fa fa-link",
                title: "Create Link",
            },
            {
                name: "embed",
                 action: function(editor) {
                     if(window.InsertMediaModal) {
                         let dialog = $('#insert-md-embed-react__dialog-wrapper');
                         if (!dialog.length) {
                             dialog = $('<div id="insert-md-embed-react__dialog-wrapper" />');
                             $('body').append(dialog);
                         }
                         dialog.setElement(editor);
                         dialog.open();
                     }
                     else {
                        alert('Media embed is not supported');
                     }
                 },
                className: "fa fa-play",
                title: "Insert Image",
            },
            {
                name: "image",
                 action: function(editor) {
                     if(window.InsertMediaModal) {
                         let dialog = $('#insert-md-media-react__dialog-wrapper');
                         if (!dialog.length) {
                             dialog = $('<div id="insert-md-media-react__dialog-wrapper" />');
                             $('body').append(dialog);
                         }
                         dialog.setElement(editor);
                         dialog.open();
                     }
                     else {
                        SimpleMDE.drawImage(editor);
                     }

                 },
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
        ];




});



class MarkdownEditorField extends React.Component {
    constructor(props) {
        super(props);
        this.state = markdownConfigs;
    }

    handleChange(value) {
        this.props.textarea.value = value;
    }

    render() {
        return (<div className="editor-container">
            <ReactSimpleMDE
                value = {this.props.textarea.value}
                onChange={this.handleChange.bind(this)}
                options={{
                    spellChecker: true,
                    dragDrop: false,
                    keyMap: "sublime",
                    toolbar: this.state.toolbar
                }}
                ></ReactSimpleMDE>
        </div>);
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


export default { MarkdownEditorField };