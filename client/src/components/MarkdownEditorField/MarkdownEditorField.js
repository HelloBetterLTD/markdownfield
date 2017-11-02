'use strict';

import React from 'react';
import ReactDOM from 'react-dom';
const SimpleMDE = require('simplemde');
import ReactSimpleMDE from 'react-simplemde-editor';
import { provideInjector } from 'lib/Injector';
import jQuery from 'jquery';
import ShortcodeParser from '../ShortCodeParser/ShortcodeParser';
const parser = new ShortcodeParser();

var ss = typeof window.ss !== 'undefined' ? window.ss : {};
if(typeof ss.markdownConfigs == 'undefined') {
    ss.markdownConfigs = {};
}


ss.markdownConfigs.readToolbarConfigs = function(data) {
    let toolbar = [];
    for (var key in data) {
        var element = data[key];
        if(typeof element == 'string') {
            toolbar.push(element);
        }
        else {
            let action = element.action;
            if(typeof SimpleMDE[element.action] !== 'undefined') {
                toolbar.push({
                    name            : element.name,
                    action          : SimpleMDE[element.action],
                    className       : element.className,
                    title           : element.title
                });
            }
            else if(typeof ss.markdownConfigs[element.action] !== 'undefined') {
                toolbar.push({
                    name            : element.name,
                    action          : function(editor){
                        ss.markdownConfigs[action](editor);
                    },
                    className       : element.className,
                    title           : element.title
                });
            }
        }
    }
    return toolbar;
}

parser.registerShortCode('image_link', function(buffer, opts) {
    return opts.url;
});


parser.registerShortCode('embed', function(buffer, opts) {
    return '<img src="' + opts.thumbnail + '" width="' + opts.width + '" height="' + opts.height + '">';
});


class MarkdownEditorField extends React.Component {
    constructor(props) {
        super(props);
        this.state = ss.markdownConfigs;
    }

    handleChange(value) {
        this.props.textarea.value = value;
    }

    previewRender(plainText, preview){
        preview.classList.add('markdown-preview');
        preview.classList.add(this.identifier);
        let parsedText = parser.parse(plainText);
        return this.parent.markdown(parsedText);
    }

    static addCustomAction(key, action) {
        ss.markdownConfigs[key] = action;
    };

    static registerShortCodes(key, callback) {

    }

    render() {
        return (<div className="editor-container">
            <ReactSimpleMDE
                value = {this.props.textarea.value}
                onChange={this.handleChange.bind(this)}
                options={{
                    spellChecker: true,
                    dragDrop    : false,
                    keyMap      : "sublime",
                    toolbar     : this.props.toolbar,
                    previewRender: this.previewRender,
                    identifier  : this.props.identifier
                }}
            ></ReactSimpleMDE>
        </div>);
    }
}


window.MarkdownEditorField = MarkdownEditorField;

jQuery.entwine('ss', ($) => {


    MarkdownEditorField.addCustomAction('ssEmbed', function(editor){
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
    });

    MarkdownEditorField.addCustomAction('ssImage', function(editor){
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
    });




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
            let data = JSON.parse(textArea.dataset.config);
            let toolbar = ss.markdownConfigs.readToolbarConfigs(data.toolbar);

            ReactDOM.render(
                <MarkdownEditorField textarea={textArea} toolbar={toolbar} identifier={data.identifier}></MarkdownEditorField>,
                this[0]
            );
        }
    });
});


export default { MarkdownEditorField };