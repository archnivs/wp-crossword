(function( $ ) {
	'use strict';
    
    var loader = '<div class="loader-12"></div>';

    $(document).ready(function() {
        
        //$("#xw").html(loader);
        
        var cw_id = $("#xw").attr("data-crossword-id");
        var d = {
           'action' : 'get_crossword',
           'data' : { 'cw_id' : cw_id } 
        };
        
        
        $.post(
        ajax_object.ajax_url,
        d,
        function(response){
            
            if(response.success) {
                
                var words = [];
                var clues = [];
                
                $(response.data.words_clues).each(function(i,v){
                
                    var _empty_word = ( v.word ) ? false : true; 
                    
                    if ( _empty_word ) return true; 
                    
                    words[i] = v.word;
                    clues[i] = v.clue;
                    
                }); 
                
                
                
                var cw = new Crossword(words, clues);
                var grid = response.data.grid;
                
                var CurrentDisplayHTML = CrosswordUtils.toHtml(grid, false);
                
                $('.loader-12').remove();
                
                $(CurrentDisplayHTML).appendTo("#xw");
                
                $("#xw").slideDown("slow")
                
                var legend = cw.getLegend(grid);            
               console.log(legend);
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
                                                
                $(legendHTML).appendTo("#xw").show("slow");    
                
                generateLegend(legend);
                
                generateCrosswordInput(grid);
            
                $("#xw").on("focus", ".crossword-input" , function(e){
                   // $(".crossword-cell").removeClass("xw-input-focus");
                    $(this).parent().addClass("xw-input-focus");
                })
                $("#xw").on("focusout", ".crossword-input" , function(e){
                    $(this).parent().removeClass("xw-input-focus");
                })
            }
        });
        
    });
    
    function generateCrosswordInput(grid) {
        
        //var input = '<input class="crossword-input" />';
        
        for(var r = 0; r < grid.length; r++){            
            for(var c = 0; c < grid[r].length; c++){
                var cell = grid[r][c];
                if(cell) {
                    $('#crossword-' + r + '-' + c).html('<input type="text" class="crossword-input" maxlength="1" />');                    
                }
            }
        }
        
        
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
