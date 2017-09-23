'use strict';

import React from 'react';
import ReactDOM from 'react-dom';
import SimpleMDE from 'react-simplemde-editor';

class MarkdownEditorField extends React.Component {
    constructor(props) {
        super(props);
    }

    handleChange(value) {
        this.props.textarea.value = value;
    }

    render() {
        return (
            <div className="editor-container">
                <SimpleMDE
                    value = {this.props.textarea.value}
                    onChange={this.handleChange.bind(this)}
                    ></SimpleMDE>
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