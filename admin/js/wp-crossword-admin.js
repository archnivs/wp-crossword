


(function( $ ) {
	'use strict';
    
    var _row = '';    
    var _cw = {};
    var loader = '<div class="loader-12"></div>';
    
    function get_row_dom(index) {
        
            var _i = index + 1;
        
            return '<tr class="word-clue-row">' + 
                        '<td class="index-col row-title">'+ _i +'</td>' +
                        '<td class="word-col"><input type="text" name="cw_word['+index+']" class="cw-word all-options" /></td>' +
                        '<td class="clue-col"><input type="text" name="cw_clue['+index+']" class="cw-clue large-text" /></td>' +
                        '<td class="remove-col"><a class="button-secondary" href="#"><span class="dashicons dashicons-dismiss"></span> Remove</a></td>' +
                    '</tr>';
    }
    
    function escapeHtml(text) {
      var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };

      return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    $(document).ready(function() {
        
        var clipboard = new Clipboard('#copy-shortcode-clipboard');
        
        generateCrossword();
        
        $('#adding-cw-word').click(function(e){
            
            var _current_row_lenth = $('.word-clue-row').length;
            
            if(_current_row_lenth >= 20) {
                
                $('.max-word-error').remove();
                $(".last-row").append('<div class="max-word-error form-invalid">A maximum of 20 words only</div>');
                return false;
                
            }
            
            _row = get_row_dom(_current_row_lenth++);
            
            $('#words-clue tbody').append(_row);
            
            return false;
            
        });
        
        $('#words-clue').on('click','.remove-col a', function(e){
            
             if ( $('.word-clue-row').length < 2 ) {
                 $('.cw-word, .cw-clue').attr('value' , '');
                 return false;
             }
                 
             
             $(this).parents('.word-clue-row').remove();
            
            $(".word-clue-row").each(function(i){
               $(this).find('.cw-word').attr('name', 'cw_word['+i+']');
               $(this).find('.cw-clue').attr('name', 'cw_clue['+i+']');
               
               var _i = i + 1;
               
               $(this).find('.index-col').text(''+ _i +'');
            });
            
            $('.max-word-error').remove();
            
            return false;
            
        });
        
        $("#generate-crossword").click(function(e){
            
             var words = [];
             var clues = [];
             
             $("#xw").html(loader);
             
            $(".word-clue-row").each(function(i){
                
                var _empty_word = ($(this).find('.cw-word').val()) ? false : true; 
                
                if ( _empty_word ) return true; 
                
                words[i] = $(this).find('.cw-word').val();
                clues[i] = $(this).find('.cw-clue').val();
                
            }); 
            
            var cw = new Crossword(words, clues);
                   
            var tries = 50; 
            var grid = cw.getSquareGrid(tries);
            
            var gridJSON = escapeHtml(JSON.stringify(grid));
            
            
            if(grid == null){
                var bad_words = cw.getBadWords();
                var str = [];
                for(var i = 0; i < bad_words.length; i++){
                    str.push(bad_words[i].word);
                }
                alert("Unabled to fit in words:\n" + str.join("\n"));
                return;
            }
                       
            var CurrentDisplayHTML = CrosswordUtils.toHtml(grid, true);
            
            
            $("#xw").delay(800).html(CurrentDisplayHTML);
            $('.loader-12').remove();
            $('#grid-json').val(gridJSON);
            
            var legend = cw.getLegend(grid);            
           
            var legendHTML =    '<table id="legend-wrap">' +            
                                            '<thead>' +
                                            '<tr>' +
                                            '<th class="row-title"><strong>Across</strong></th>' +
                                            '<th class="row-title"><strong>Down</strong></th>'  +
                                            '</tr>'  +
                                            '</thead>' +                                
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td><ul id="across"></ul></td>' +
                                            '<td><ul id="down"></ul></td>'  +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>';
                                            
            $("#xw").append(legendHTML);
            
            generateLegend(legend);
            
            return false;
        });
        
    });
    
    function generateCrossword() {
        
        var words = [];
         var clues = [];
         
         if ( $('.word-clue-row').length < 2 ) {
             $('.cw-word, .cw-clue').attr('value' , '');
             return false;
        }
         
        $(".word-clue-row").each(function(i){
            
            var _empty_word = ($(this).find('.cw-word').val() ) ? false : true; 
            
            if ( _empty_word ) return true; 
            
            words[i] = $(this).find('.cw-word').val();
            clues[i] = $(this).find('.cw-clue').val();
            
        }); 
        
        var cw = new Crossword(words, clues);
        var grid = JSON.parse($('#grid-json').val());
        
        var CurrentDisplayHTML = CrosswordUtils.toHtml(grid, true);
            $("#xw").html(CurrentDisplayHTML);
            
            var legend = cw.getLegend(grid);            
           
            var legendHTML =    '<table id="legend-wrap">' +            
                                            '<thead>' +
                                            '<tr>' +
                                            '<th class="row-title"><strong>Across</strong></th>' +
                                            '<th class="row-title"><strong>Down</strong></th>'  +
                                            '</tr>'  +
                                            '</thead>' +                                
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td><ul id="across"></ul></td>' +
                                            '<td><ul id="down"></ul></td>'  +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>';
                                            
            $("#xw").append(legendHTML);
            
            generateLegend(legend);
    }
    
    
    function generateLegend(groups) {
            
        for (var k in groups) {
            var html = [];
            for (var i = 0; i < groups[k].length; i++) {
                var clue_input = '<span style="display: inline-block; border-bottom: 1px solid black; color: #aaa;">' + groups[k][i]['word'].toLowerCase() + ' </span>';
                var clue = groups[k][i]['clue'];
                //var escaped_clue = escape(clue).replace(/&lt;i&gt;/g, '<i>').replace(/&lt;\/i&gt;/g, '</i>');
                var escaped_clue = clue;
                var parsed_clue = '';
                if (escaped_clue.match(/__+/)) {
                    parsed_clue = escaped_clue.replace(/__+/, clue_input);
                } else {
                    parsed_clue = escaped_clue += '; ' + clue_input;
                }
                html.push('<li class="clue-cell"><strong>' + groups[k][i]['position'] + '.</strong> ' + parsed_clue + '</li>');
            }
            document.getElementById(k).innerHTML = html.join("\n");
        }
    };

})( jQuery );
