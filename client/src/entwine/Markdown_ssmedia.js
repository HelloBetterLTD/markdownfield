import jQuery from 'jquery';
import React from 'react';
import ReactDOM from 'react-dom';

import { ApolloProvider } from 'react-apollo';
import { provideInjector } from 'lib/Injector';
const InjectableInsertMediaModal = provideInjector(window.InsertMediaModal.default);


jQuery.entwine('ss', ($) => {

    $('#insert-md-media-react__dialog-wrapper').entwine({

        Element: null,
        Data: {},
        onunmatch() {
            // solves errors given by ReactDOM "no matched root found" error.
            this._clearModal();
        },
        _clearModal() {
            ReactDOM.unmountComponentAtNode(this[0]);
        },
        open() {
            this._renderModal(true);
        },
        close() {
            this._renderModal(false);
        },

        /**
         * Renders the react modal component
         *
         * @param {boolean} show
         * @private
         */
        _renderModal(show) {
            const handleHide = () => this.close();
            const handleInsert = (...args) => this._handleInsert(...args);
            const store = window.ss.store;
            const client = window.ss.apolloClient;
            const attrs = this.getOriginalAttributes();

            delete attrs.url;

            // create/update the react component
            ReactDOM.render(
            <ApolloProvider store={store} client={client}>
                <InjectableInsertMediaModal
                    title={false}
                    type="insert-media"
                    show={show}
                    onInsert={handleInsert}
                    onHide={handleHide}
                    bodyClassName="modal__dialog"
                    className="insert-media-react__dialog-wrapper"
                    requireLinkText={false}
                    fileAttributes={attrs}
                />
                </ApolloProvider>,
                this[0]
        );
        },

        /**
         * Handles inserting the selected file in the modal
         *
         * @param {object} data
         * @param {object} file
         * @returns {Promise}
         * @private
         */
        _handleInsert(data, file) {
            let result = false;
            this.setData(Object.assign({}, data, file));

            // Sometimes AssetAdmin.js handleSubmitEditor() can't find the file
            // @todo Ensure that we always return a file for any valid ID

            // in case of any errors, better to catch them than let them go silent
            try {
                let category = null;
                if (file) {
                    category = file.category;
                } else {
                    category = 'image';
                }
                switch (category) {
                    case 'image':
                        result = this.insertImage();
                        break;
                    default:
                        result = this.insertFile();
                }
            } catch (e) {
                this.statusMessage(e, 'bad');
            }

            if (result) {
                this.close();
            }
            return Promise.resolve();
        },

        /**
         * Find the selected node and get attributes associated to attach the data to the form
         *
         * @returns {object}
         */
        getOriginalAttributes() {
            return {};
        },

        /**
         * Calculate placement from css class
         */
        findPosition(cssClass) {
            const alignments = [
                'leftAlone',
                'center',
                'rightAlone',
                'left',
                'right',
            ];
            return alignments.find((alignment) => {
                const expr = new RegExp(`\\b${alignment}\\b`);
                return expr.test(cssClass);
            });
        },

        /**
         * Get html attributes from the Form data
         *
         * @returns {object}
         */
        getAttributes() {
            const data = this.getData();

            return {
                src: data.url,
                alt: data.AltText,
                width: data.InsertWidth,
                height: data.InsertHeight,
                title: data.TitleTooltip,
                class: data.Alignment,
                'data-id': data.ID,
                'data-shortcode': 'image',
            };
        },

        /**
         * Get extra data not part of the actual element we're adding/modifying (e.g. Caption)
         * @returns {object}
         */
        getExtraData() {
            const data = this.getData();
            return {
                CaptionText: data && data.Caption,
            };
        },

        /**
         * Generic handler for inserting a file
         *
         * NOTE: currently not supported
         *
         * @returns {boolean} success
         */
        insertFile() {
            const $field = this.getElement();
            const data = this.getData();
            let linkText = data.title || data.filename;
            let markdown = '[' + linkText + '](' + data.url + ')';

            let pos = $field.codemirror.getCursor();
            $field.codemirror.setSelection(pos, pos);
            $field.codemirror.replaceSelection("\n" + markdown + "\n");
            this.updateTextarea();
            return true;
        },

        /**
         * Handler for inserting an image
         *
         * @returns {boolean} success
         */
        insertImage() {
            const $field = this.getElement();
            if (!$field) {
                return false;
            }

            const attrs = this.getAttributes();
            const extraData = this.getExtraData();

            let markdown = '!['
                + (extraData.CaptionText ? extraData.CaptionText : attrs.title)
                + ']('+ attrs.src +' "'
                + attrs.title
                + '")';

            let pos = $field.codemirror.getCursor();
            $field.codemirror.setSelection(pos, pos);
            $field.codemirror.replaceSelection("\n" + markdown + "\n");
            this.updateTextarea();
            return true;
        },

        /**
         * Pop up a status message if required to notify the user what is happening
         *
         * @param text
         * @param type
         */
        statusMessage(text, type) {
            const content = $('<div/>').text(text).html(); // Escape HTML entities in text
            $.noticeAdd({
                text: content,
                type,
                stayTime: 5000,
                inEffect: { left: '0', opacity: 'show' },
            });
        },


        updateTextarea()
        {
            const $field = this.getElement();
            $($field.element).closest('.js-markdown-holder')
                .find('textarea.markdowneditor').val($field.value());
        }

    });

});