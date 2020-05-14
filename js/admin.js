jQuery(document).ready(function($) {
    $.get( "/wp-json/osom-recrutation/v1/submissions", function( data ) {
        jQuery(data).each(function(i,item) {
            let rowHtml = "<tr>";
            rowHtml += "<td>"+item.id+"</td>"
            rowHtml += "<td>"+item.first_name+"</td>"
            rowHtml += "<td>"+item.last_name+"</td>"
            rowHtml += "<td>"+item.login+"</td>"
            rowHtml += "<td>"+item.email+"</td>"
            rowHtml += "<td>"+item.city+"</td>"
            rowHtml += "</tr>";
            jQuery("table").append(rowHtml);
        });
    });
});