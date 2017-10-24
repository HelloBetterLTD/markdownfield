'use strict';

import React from 'react';
import ReactDOM from 'react-dom';
const SimpleMDE = require('simplemde');
import ReactSimpleMDE from 'react-simplemde-editor';
import { provideInjector } from 'lib/Injector';
import jQuery from 'jquery';




const markdownConfigs = {
    readToolbarConfigs: function(feed) {
        let data = JSON.parse(feed);
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
                else if(typeof markdownConfigs[element.action] !== 'undefined') {
                    toolbar.push({
                        name            : element.name,
                        action          : function(editor){
                            markdownConfigs[action](editor);
                        },
                        className       : element.className,
                        title           : element.title
                    });
                }
            }
        }
        return toolbar;
    }
}



class MarkdownEditorField extends React.Component {
    constructor(props) {
        super(props);
        this.state = markdownConfigs;
    }

    handleChange(value) {
        this.props.textarea.value = value;
    }

    static addCustomAction(key, action) {
        markdownConfigs[key] = action;
    };

    render() {
        return (<div className="editor-container">
            <ReactSimpleMDE
                value = {this.props.textarea.value}
                onChange={this.handleChange.bind(this)}
                options={{
                    spellChecker: true,
                        dragDrop: false,
                        keyMap: "sublime",
                        toolbar: this.props.toolbar
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
            let toolbar = markdownConfigs.readToolbarConfigs(textArea.dataset.config);

            ReactDOM.render(
                <MarkdownEditorField textarea={textArea} toolbar={toolbar}></MarkdownEditorField>,
                this[0]
            );
        }
    });
});


export default { MarkdownEditorField };