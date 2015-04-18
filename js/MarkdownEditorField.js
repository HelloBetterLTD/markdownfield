/**
 * Created by nivankafonseka on 4/18/15.
 */
(function($){

    $.entwine('MarkdownField', function(){

        function showPreview(dom){
            var holder = $(dom).closest('.field.markdowneditor');
            holder.find('.editor-holder').hide();
            holder.find('.preview-pane').show().html('').addClass('loading');
            holder.find('.markdown-tabs').find('a').removeClass('active');
            $(dom).addClass('active');

            $.ajax({
                method      : "POST",
                url         : holder.data('preview'),
                dataType    : "html",
                data        : {
                    markdown : holder.find('textarea.markdowneditor').val()
                },
                success : function(data){
                    holder.find('.preview-pane').html(data).removeClass('loading');
                }
            });

        }

        function showEditor(dom){
            var holder = $(dom).closest('.field.markdowneditor');
            holder.find('.markdown-tabs').find('a').removeClass('active');
            $(dom).addClass('active');
            holder.find('.editor-holder').show();
            holder.find('.preview-pane').hide();
        }


        $('.field.markdowneditor').entwine({

            onmatch: function() {

                var textarea = $(this).find('textarea');
                var div = $(this).find('.editor-div');
                var editor = ace.edit(div.attr('id'));
                editor.setTheme("ace/theme/github");
                editor.getSession().setMode("ace/mode/markdown");

                editor.getSession().on('change', function(){
                    textarea.val(editor.getSession().getValue());
                });

                $(this).data('ace', editor);

            }

        });


        $('.markdown-tabs').entwine({

            onmatch: function(){
                $(this).find('a.edit').click(function(){
                    showEditor(this);
                    return false;
                });


                $(this).find('a.preview').click(function(){
                    showPreview(this);
                    return false;
                });
            }

        });



    });



})(jQuery);